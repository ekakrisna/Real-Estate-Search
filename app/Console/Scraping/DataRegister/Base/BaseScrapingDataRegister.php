<?php

namespace App\Console\Scraping\DataRegister\Base;

use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\CustomerNew;
use App\Models\PropertyConvertStatus;

abstract class BaseScrapingDataRegister
{
    protected $_scrapingOrm = null;
    protected $_scrapingLogOrm = null;
    protected $_scrapingPublishOrm = null;

    protected $_propertyOrm = null;
    protected $_propertyStatusOrm = null;
    protected $_propertyPublishOrm = null;
    protected $_propertyLogActivityhOrm = null;
    protected $_propertyScrapingTypeOrm = null;
    protected $_propertyConvertStatusOrm = null;

    protected $is_insert_finish_status = false;
    protected $is_insert_news = false;
    protected $is_add_condtion_for_back_up = false;

    private $scrapingRowArray = [];

    public function addData($scrapingRow)
    {
        array_push($this->scrapingRowArray, $scrapingRow);
    }

    /**
     * Retrieving data from Octopers and storing it in a scraping table
     *
     * @return void
     */
    public function dataRegister($dayOfWeek = null): bool
    {
        $result = true;

        if (count($this->scrapingRowArray) == 0) {
            return $result;
        }

        DB::beginTransaction();
        try {
            $this->saveData2ScrapingTable($this->scrapingRowArray, $dayOfWeek);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info("Error convartError" . Carbon::now() . $e);
            $result =  false;

            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            sendMessageOfErrorReport("BaseScrappingDataRegister (dataRegister Function), Error: ", $e);
            //------------------------------------------------------

        } finally {
            if ($result) {
                if ($this->is_insert_finish_status) {
                    $this->insertFinishStatus($this->scrapingRowArray, $dayOfWeek);
                }
                $this->refreshPropertiesTable();
            }
        }
        return  $result;
    }

    private function saveData2ScrapingTable($scrapingRowArray, $dayOfWeek)
    {

        foreach ($scrapingRowArray as $scrapingRow) {
            $scrapingModel = null;

            $scrapingModel = $this->getScrapingModelFromURL($scrapingRow->url);

            if ($scrapingRow->location != null && $scrapingModel == null) {
                $scrapingModel = $this->getScrapingModel($scrapingRow, $scrapingRow->location);
            }

            if ($scrapingModel == null) {
                $scrapingModel = $this->getScrapingModel($scrapingRow, $scrapingRow->location_original);
            }

            $location = null;
            if ($scrapingModel == null && $scrapingRow->location == null) {
                $location = $scrapingRow->location_original;
            } else {
                $location = $scrapingRow->location;
            }
            $scrapingRow->location = $location;

            // Insert data
            if ($scrapingModel == null) {
                $scrapingModel = $this->_scrapingOrm::create((array)$scrapingRow);

                // create publish model
                $this->createScrapingPublish($scrapingModel, $scrapingRow, $dayOfWeek);

                // create log of register
                $this->insertScrapingLog($scrapingModel->id, $this->_scrapingLogOrm::NEW_REGISTER);

                // create log if there are convart error
                if (!$scrapingRow->validate_land_area) {
                    $this->insertScrapingLog($scrapingModel->id, $this->_scrapingLogOrm::WRONG_LOAND_AREA);
                }

                if (!$scrapingRow->validate_price) {
                    $this->insertScrapingLog($scrapingModel->id, $this->_scrapingLogOrm::WRONG_PRICE);
                }

                if (!$scrapingRow->validate_location) {
                    $this->insertScrapingLog($scrapingModel->id, $this->_scrapingLogOrm::WRONG_LOCATION);
                }
                continue;
            }

            // update data if data is already stored
            $isChange = false;

            if ($scrapingModel->minimum_price != $scrapingRow->minimum_price || $scrapingModel->maximum_price != $scrapingRow->maximum_price) {
                $this->insertScrapingLog($scrapingModel->id, $this->_scrapingLogOrm::UPDATE_PRICE);
                $isChange = true;
            }

            if ($scrapingModel->minimum_land_area != $scrapingRow->minimum_land_area || $scrapingModel->maximum_land_area != $scrapingRow->maximum_land_area) {
                $this->insertScrapingLog($scrapingModel->id, $this->_scrapingLogOrm::UPDATE_LOAND_AREA);
                $isChange = true;
            }


            $scrapingPublishModel = $this->_scrapingPublishOrm::ScrapingId($scrapingModel->id)
                ->where('url', $scrapingRow->url)
                ->where('publication_destination', $scrapingRow->publication_destination)
                ->first();

            if ($scrapingPublishModel === null) {
                $this->createScrapingPublish($scrapingModel, $scrapingRow, $dayOfWeek);
            } else {
                $scrapingPublishModel->property_number = $scrapingRow->property_number;
                $scrapingPublishModel->day_of_week = $dayOfWeek;
                $scrapingPublishModel->save();
            }

            // update_scraping_data
            $scrapingModel->update((array)$scrapingRow);
            if (!$isChange) {
                $this->insertScrapingLog($scrapingModel->id, $this->_scrapingLogOrm::NO_UPDATE);
            }
        }
    }

    /**
     * Save data in the scraping table data to the property table
     *
     * @return void
     */
    private function refreshPropertiesTable()
    {
        $scrapingLogList = $this->_scrapingLogOrm::where('is_adapt', false)->get();
        foreach ($scrapingLogList as $scrapingLog) {
            try {
                // Get property object
                // Get object from relation if already exist to save in table.
                $property = new $this->_propertyOrm();
                if ($this->_scrapingLogOrm::NEW_REGISTER != $scrapingLog->status) {
                    $property =  $scrapingLog->scraping->property;
                }

                //
                // Skip to next loop If scrapingLog is no update and proerpty is already update(already fixed by user).
                //
                switch ($scrapingLog->status) {
                    case $this->_scrapingLogOrm::WRONG_PRICE:
                    case $this->_scrapingLogOrm::WRONG_LOAND_AREA:
                    case $this->_scrapingLogOrm::WRONG_LOCATION:

                        if ($property->property_convert_status_id == $this->_propertyConvertStatusOrm::ALRADY_UPDATE) {
                            $scrapingLog->is_adapt = true;
                            $scrapingLog->save();
                            continue 2;
                        }
                        break;

                    case $this->_scrapingLogOrm::NO_UPDATE:

                        if ($property->property_status_id != $this->_propertyStatusOrm::FINISH_PUBLISH) {
                            $scrapingLog->is_adapt = true;
                            $scrapingLog->save();

                            foreach ($scrapingLog->scraping->scraping_publish as $scraping_publishModel) {
                                $propertyPublishModel = $this->_propertyPublishOrm::PropertyId($property->id)
                                    ->where('publication_destination', $scraping_publishModel->publication_destination)
                                    ->where('url', $scraping_publishModel->url)->first();

                                if ($propertyPublishModel === null) {
                                    $propertyPublishModel = new $this->_propertyPublishOrm;
                                    $propertyPublishModel->property_id = $property->id;
                                    $propertyPublishModel->url = $scraping_publishModel->url;
                                    $propertyPublishModel->publication_destination = $scraping_publishModel->publication_destination;
                                    $propertyPublishModel->property_number = $scraping_publishModel->property_number;
                                    $propertyPublishModel->save();
                                }
                            }
                            continue 2;
                        }
                }

                //
                // Get update type.
                //
                switch ($scrapingLog->status) {
                    case $this->_scrapingLogOrm::NEW_REGISTER:
                        $property_scraping_type_id = $this->_propertyScrapingTypeOrm::CREATE;
                        $property_status_id = $this->_propertyStatusOrm::APPROVAL_PENDING;
                        break;

                    case $this->_scrapingLogOrm::UPDATE_PRICE:
                    case $this->_scrapingLogOrm::UPDATE_LOAND_AREA:
                        $property_scraping_type_id = $this->_propertyScrapingTypeOrm::UPDATE;
                        $property_status_id = $property->property_status_id;
                        break;

                    case $this->_scrapingLogOrm::WRONG_PRICE:
                    case $this->_scrapingLogOrm::WRONG_LOAND_AREA:
                    case $this->_scrapingLogOrm::WRONG_LOCATION:
                        $property_scraping_type_id = $this->_propertyScrapingTypeOrm::CREATE;
                        $property_status_id = $this->_propertyStatusOrm::APPROVAL_PENDING;
                        break;

                    case $this->_scrapingLogOrm::FINISH_PUBLISH:
                        $property_scraping_type_id = $this->_propertyScrapingTypeOrm::DELETE;
                        $property_status_id = $this->_propertyStatusOrm::FINISH_PUBLISH;
                        break;

                    case $this->_scrapingLogOrm::NO_UPDATE:
                        $property_scraping_type_id = $this->_propertyScrapingTypeOrm::UPDATE;
                        if ($property->property_status_id == $this->_propertyStatusOrm::FINISH_PUBLISH) {
                            $property_status_id = $this->_propertyStatusOrm::PUBLISHED;
                        } else {
                            $property_status_id = $property->property_status_id;
                        }
                        break;

                    default:
                        $property_scraping_type_id = $property->_propertyScrapingTypeOrm::DELETE;
                        $property_status_id = $property->_propertyStatusOrm::FINISH_PUBLISH;
                        break;
                }

                // Get Convert result
                $property_convert_status_id = $this->_scrapingLogOrm::convertScrapingLog($scrapingLog->status);

                // Get publish date
                $publish_date = Carbon::now();
                $isUpdateTimeStamp = true;
                if ($this->_scrapingLogOrm::NEW_REGISTER != $scrapingLog->status) {
                    $publish_date = $property->publish_date;
                    $isUpdateTimeStamp = false;
                }

                // add Foreign key to properyt table
                $property->timestamps  = $isUpdateTimeStamp;
                $property->scraping_id = $scrapingLog->scraping->id;
                $property->property_status_id = $property_status_id;
                $property->scraping_type_id = $property_scraping_type_id;
                $property->convert_status_id = $property_convert_status_id;
                $property->publish_date = $publish_date;

                if ($this->_scrapingLogOrm::NEW_REGISTER == $scrapingLog->status) {
                    // fill other date
                    $property->fill($scrapingLog->scraping->toArray());
                }

                // each scraping table
                $property->fillDataFromScrapingLog($scrapingLog);
                $property->save();

                //
                // update property log
                //
                switch ($scrapingLog->status) {

                    case $this->_scrapingLogOrm::NEW_REGISTER:
                        $this->updatePropertyLog(
                            $this->_propertyScrapingTypeOrm::CREATE,
                            $property->id
                        );
                        break;

                    case $this->_scrapingLogOrm::UPDATE_PRICE:
                        $property_max_price = $property['maximum_price'] === null ? '' : '~' . toManDisplay($property['maximum_price']);
                        $scraping_max_price =  $scrapingLog->scraping['maximum_price'] === null ? '' : '~' . toManDisplay($scrapingLog->scraping['maximum_price']);

                        $this->updatePropertyLog(
                            $this->_propertyScrapingTypeOrm::UPDATE,
                            $property->id,
                            toManDisplay($property['minimum_price']) . $property_max_price,
                            toManDisplay($scrapingLog->scraping['minimum_price']) . $scraping_max_price,
                            '価格'
                        );

                        if (
                            $this->is_insert_news &&
                            (
                                $property->property_convert_status_id == $this->_propertyConvertStatusOrm::SUCCESSFUL ||
                                $property->property_convert_status_id == $this->_propertyConvertStatusOrm::ALRADY_UPDATE
                            )
                        ) {
                            CustomerNew::storeNews($property, CustomerNew::PROPERTY_UPDATE);
                        }
                        break;

                    case $this->_scrapingLogOrm::UPDATE_LOAND_AREA:
                        $property_max_land_area = $property['maximum_land_area'] === null ? '' : '~' . number_format(toTsubo($property['maximum_land_area']), 0) . "坪(" . $property['maximum_land_area'] . "㎡)";
                        $scraping_max_land_area =  $scrapingLog->scraping['maximum_land_area'] === null ? '' : '~' . number_format(toTsubo($scrapingLog->scraping['maximum_land_area']), 0) . "坪(" . $scrapingLog->scraping['maximum_land_area'] . "㎡)";

                        $this->updatePropertyLog(
                            $this->_propertyScrapingTypeOrm::UPDATE,
                            $property->id,
                            number_format(toTsubo($property['minimum_land_area']), 0) . "坪(" . $property['minimum_land_area'] . "㎡)" . $property_max_land_area,
                            number_format(toTsubo($scrapingLog->scraping['minimum_land_area']), 0) . "坪(" . $scrapingLog->scraping['minimum_land_area'] . "㎡)" . $scraping_max_land_area,
                            '土地面積'
                        );

                        if (
                            $this->is_insert_news &&
                            (
                                $property->property_convert_status_id == $this->_propertyConvertStatusOrm::SUCCESSFUL ||
                                $property->property_convert_status_id == $this->_propertyConvertStatusOrm::ALRADY_UPDATE
                            )
                        ) {
                            CustomerNew::storeNews($property, CustomerNew::PROPERTY_UPDATE);
                        }
                        break;

                    case $this->_scrapingLogOrm::FINISH_PUBLISH:
                        $this->updatePropertyLog(
                            $this->_propertyScrapingTypeOrm::DELETE,
                            $property->id
                        );

                        // create news
                        if ($this->is_insert_news) {
                            CustomerNew::storeNews($property, CustomerNew::PROPERTY_END);
                        }
                        break;
                }

                foreach ($scrapingLog->scraping->scraping_publish as $scraping_publishModel) {
                    $propertyPublishModel = $this->_propertyPublishOrm::PropertyId($property->id)
                        ->where('publication_destination', $scraping_publishModel->publication_destination)
                        ->where('url', $scraping_publishModel->url)->first();

                    if ($propertyPublishModel === null) {
                        $propertyPublishModel = new $this->_propertyPublishOrm;
                        $propertyPublishModel->property_id = $property->id;
                        $propertyPublishModel->url = $scraping_publishModel->url;
                        $propertyPublishModel->publication_destination = $scraping_publishModel->publication_destination;
                        $propertyPublishModel->property_number = $scraping_publishModel->property_number;
                        $propertyPublishModel->save();
                    } else {
                        $propertyPublishModel->property_number = $scraping_publishModel->property_number;
                        $propertyPublishModel->save();
                    }
                }

                $scrapingLog->is_adapt = true;
                $scrapingLog->save();
            } catch (\Exception $e) {
                Log::info("Error convartScraping2Properties" . Carbon::now() . print_r($scrapingLog, true) . $e);
                //------------------------------------------------------
                //Send chat to chatwork if failing in function
                //------------------------------------------------------
                sendMessageOfErrorReport("BaseScrappingDataRegister (refreshPropertiesTable Function), Error: ", $e);
                //------------------------------------------------------
            }
        }
    }

    /**
     * Create log finish status if data
     *
     * @return void
     */
    private function insertFinishStatus($scrapingRowArray, $dayOfWeek)
    {

        $publisherName = "";
        foreach ($scrapingRowArray as $scrapingRow) {
            $publisherName = $scrapingRow->publication_destination;
            break;
        }

        if ($publisherName == "") {
            return;
        }

        $notUpdatePreIds =  $this->_scrapingLogOrm::selectRaw('Max(id)')->groupBy('scraping_id')->get()->toArray();
        $scrapingIds = $this->_scrapingLogOrm::whereIn('id', $notUpdatePreIds)->where('status', '<>', $this->_scrapingLogOrm::FINISH_PUBLISH)->where('is_adapt', 1)->pluck('scraping_id')->toArray();
        $scrapingArray = $this->_scrapingPublishOrm::where('publication_destination', $publisherName)->whereIn('scraping_id', $scrapingIds)->where('day_of_week', $dayOfWeek)->get();

        $alreadyExistsScrapingIdArray = [];

        foreach ($scrapingArray as $scrapingModel) {
            if (in_array($scrapingModel->scraping_id, $alreadyExistsScrapingIdArray)) {
                continue;
            }
            $alreadyExistsScrapingIdArray[] = $scrapingModel->scraping_id;
            $this->insertScrapingLog($scrapingModel->scraping_id, $this->_scrapingLogOrm::FINISH_PUBLISH);
        }
    }

    private function insertScrapingLog($scraping_id, $status)
    {
        $scrapingLogModel = new $this->_scrapingLogOrm();
        $scrapingLogModel->scraping_id = $scraping_id;
        $scrapingLogModel->is_adapt = false;
        $scrapingLogModel->status = $status;
        $scrapingLogModel->save();
    }

    private function updatePropertyLog($status, $propertyId, $oldValue = "", $newValue = "", $columnName = "")
    {
        $propertyLogActivityModel = new $this->_propertyLogActivityhOrm();
        $propertyLogActivityModel->properties_id = $propertyId;
        $propertyLogActivityModel->property_scraping_type_id = $status;

        $beforeText = $columnName . ': ' . $oldValue;
        $afterText = $columnName . ': ' . $newValue;
        if ($columnName === "") {
            $beforeText = "";
            $afterText = "";
        }

        $propertyLogActivityModel->before_update_text = $beforeText;
        $propertyLogActivityModel->after_update_text = $afterText;
        $propertyLogActivityModel->created_at = Carbon::now();
        $propertyLogActivityModel->save();
    }

    private function getScrapingModelFromURL($url)
    {
        $scrapingPublishModel = $this->_scrapingPublishOrm::where('url', '<>', '')->where('url', $url)->first();

        if ($scrapingPublishModel != null) {
            return $scrapingPublishModel->scraping;
        }
        return null;
    }

    private function getScrapingModel($scrapingRow, $location)
    {

        $minOfminlandArea = $scrapingRow->minimum_land_area - 3;
        $maxOfminlandArea = $scrapingRow->minimum_land_area + 3;

        $minOfmaxlandArea = null;
        $maxOfmaxlandArea = null;

        if ($scrapingRow->maximum_land_area != null) {
            $minOfmaxlandArea = $scrapingRow->maximum_land_area - 3;
            $maxOfmaxlandArea = $scrapingRow->maximum_land_area + 3;
        }

        $scrapingQuery = $this->_scrapingOrm::where('location', $location)
            ->where('minimum_price', $scrapingRow->minimum_price)
            ->where('maximum_price', $scrapingRow->maximum_price)
            ->where('minimum_land_area', ">", $minOfminlandArea)->where('minimum_land_area', "<", $maxOfminlandArea);

        if ($scrapingRow->maximum_land_area != null) {
            $scrapingQuery = $scrapingQuery->where('maximum_land_area', ">", $minOfmaxlandArea)->where('maximum_land_area', "<", $maxOfmaxlandArea);
        }

        if ($this->is_add_condtion_for_back_up === true) {
            $scrapingModel = $scrapingQuery->first();
            if ($scrapingModel != null) {
                $propertyModel = $scrapingModel->property;
                if ($propertyModel != null) {
                    $scrapingQuery = $scrapingQuery->whereHas('property', function ($query) {
                        $query->OtherThanBackUp();
                    });
                }
            }
        }

        $scrapingModel = $scrapingQuery->first();
        return $scrapingModel;
    }

    private function createScrapingPublish($scrapingModel, $scrapingRow, $dayOfWeek)
    {
        $scrapingPublishModel = new $this->_scrapingPublishOrm();
        $scrapingPublishModel->scraping_id = $scrapingModel->id;
        $scrapingPublishModel->url = $scrapingRow->url;
        $scrapingPublishModel->publication_destination = $scrapingRow->publication_destination;
        $scrapingPublishModel->property_number = $scrapingRow->property_number;
        $scrapingPublishModel->day_of_week = $dayOfWeek;
        $scrapingPublishModel->save();
    }
}
