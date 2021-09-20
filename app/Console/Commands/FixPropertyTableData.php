<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Scraping;
use App\Models\ScrapingLog;
use App\Models\ScrapingPublish;
use App\Models\Property;
use App\Models\PropertyPublish;
use App\Models\PropertyLogActivity;
use App\Models\PropertyPhoto;
use App\Models\PropertyFlyer;
use App\Models\File;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;

class FixPropertyTableData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Fix:PropertyTable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    public function handle()
    {
        DB::beginTransaction();
        $propertyIds = Property::groupBy('minimum_land_area','maximum_land_area','minimum_price','maximum_price')
        ->havingRaw('COUNT(*) = 2')->selectRaw('MAX(id) as max_id, MIN(id) as min_id')->get();

        $result = true;
        foreach($propertyIds as $propertyId){

            $old_propertyModel = Property::find($propertyId->max_id);

            /**
             * Create scraping-related tables
             */

            // get exsiting scraping table.
            $old_scrapingModel = $old_propertyModel->property_scraping()->first();

            // create new record to scraping table using old value.
            $created_scrapingModel = new Scraping();
            $created_scrapingModel->fill($old_scrapingModel->toArray())->save();

            $this->createScrapingRelatedData($old_scrapingModel,$created_scrapingModel);
            
            /**
             * Create property-related tables
             */

            // create new record to property table using old value.
            $created_propertyModel = new Property();
            $dataForCreate = $old_propertyModel->toArray();

            $dataForCreate['property_convert_status_id'] = 0;
            if(strpos($old_propertyModel->location,'地図を見る') !== false){
                $dataForCreate['property_convert_status_id'] = 100;
            }

            $dataForCreate['scraping_id'] = $created_scrapingModel->id;
            $created_propertyModel->fill($dataForCreate)->save();

            $this->createPropertyRelatedData($old_propertyModel,$created_propertyModel);
            $this->createFileTable($propertyId,PropertyPhoto::class,$created_propertyModel->id);
            $this->createFileTable($propertyId,PropertyFlyer::class,$created_propertyModel->id);
            
            if(!$this->checkCreateDataInDatabase($propertyId)){
                $this->checkCreateDataInDatabase($propertyId);
                DB::rollBack();
                $result = false;
            }
        } 

        if($result){
            DB::commit();
        }
    }

    private function createScrapingRelatedData($old_propertyModel,$created_scrapingModel){

        // create scraping_log
        foreach($old_propertyModel->scraping_log()->get() as $scrapingLog){
            $dataArrayForCreate = $scrapingLog->toArray();
            $dataArrayForCreate['scraping_id'] = $created_scrapingModel->id;
            ScrapingLog::create($dataArrayForCreate);
        }

        // create scraping_publish
        foreach($old_propertyModel->scraping_publish()->get() as $scrapingPublish){
            $dataArrayForCreate = $scrapingPublish->toArray();
            $dataArrayForCreate['scraping_id'] = $created_scrapingModel->id;
            ScrapingPublish::create($dataArrayForCreate);
        }
    }

    private function createPropertyRelatedData($old_propertyModel,$created_propertyModel){

        // create property_log_activeies
        foreach($old_propertyModel->property_log_activities()->get() as $propertyLog){
            $dataArrayForCreate = $propertyLog->toArray();
            $dataArrayForCreate['properties_id'] = $created_propertyModel->id;
            PropertyLogActivity::create($dataArrayForCreate);
        }

        // create property_publsh
        foreach($old_propertyModel->property_publish()->get() as $propertyPublish){
            $dataArrayForCreate = $propertyPublish->toArray();
            $dataArrayForCreate['properties_id'] = $created_propertyModel->id;
            PropertyPublish::create($dataArrayForCreate);
        }
    }

    private function createFileTable($propertyId,$className,$created_property_id){
        $min_propertyPotohs = $className::where('properties_id',$propertyId->min_id)->count();
        $max_propertyPotohs = $className::where('properties_id',$propertyId->max_id)->count();

        $using_property_id = null;
        if($min_propertyPotohs > $max_propertyPotohs){
            $using_property_id = $propertyId->min_id;
        }else{
            $using_property_id = $propertyId->max_id;
        }

        $oldPropertyPhotosModel = $className::where('properties_id',$using_property_id)->get();
        foreach($oldPropertyPhotosModel as $oldPropertyPhotoModel){
            foreach($oldPropertyPhotoModel->file()->get() as $oldFileModel){
                $dataArrayForCreate = $oldFileModel->toArray();
                $created_file_model = File::create($dataArrayForCreate);

                $dataArrayForCreate = $oldPropertyPhotoModel->toArray();
                $dataArrayForCreate['file_id'] = $created_file_model->id;
                $dataArrayForCreate['properties_id'] = $created_property_id;
                $created_file_model = $className::create($dataArrayForCreate);
            }
        }
    }


    private function checkCreateDataInDatabase($ids){
        $min_propertyModel = Property::find($ids['min_id']);
        $max_propertyModel = Property::find($ids['max_id']);

        $created_model = Property::where('location',$max_propertyModel->location)
        ->where('minimum_land_area',$max_propertyModel->minimum_land_area)
        ->where('maximum_land_area',$max_propertyModel->maximum_land_area)
        ->where('minimum_price',$max_propertyModel->minimum_price)
        ->where('maximum_price',$max_propertyModel->maximum_price)
        ->where('property_convert_status_id', '<>','1000')->first();

        if($created_model == null){
            return false;
        }

        if($created_model->property_scraping()->get()->isEmpty()){
            return false;
        }

        /**
         * 
         * Check the scraping-related tables.
         * 
         */

        /**
         * get old scraping model
         */
        $old_scrapingModel = $max_propertyModel->property_scraping()->first();
        $old_scrapingLog =$old_scrapingModel->scraping_log()->get();
        $old_scrapingPublish =$old_scrapingModel->scraping_publish()->get();

        /**
         * get created scraping model
         */
        $created_scrapingModel = $created_model->property_scraping()->first();
        $created_scrapingLog =$created_scrapingModel->scraping_log()->get();
        $created_scrapingPublish =$created_scrapingModel->scraping_publish()->get();

        /**
         * check existence created model
         */
        if($old_scrapingLog->count() != $created_scrapingLog->count()){
            return false;
        }
        
        if($old_scrapingPublish->count() != $created_scrapingPublish->count()){
            return false;
        }

         /**
         * 
         * Check the property-related tables.
         * 
         */

        /**
         * get old scraping model
         */
        $old_propertyLog =$max_propertyModel->property_publish()->get();
        $old_propertyPublish =$max_propertyModel->property_log_activities()->get();

        $old_min_propertyPhotoCount = $min_propertyModel->property_photos()->count();
        $old_min_propertyFlyerCount = $min_propertyModel->property_flyers()->count();

        $old_max_propertyPhotoCount = $max_propertyModel->property_photos()->count();
        $old_max_propertyFlyerCount = $max_propertyModel->property_flyers()->count();

        /**
         * get created scraping model
         */
        $created_propertyLog =$created_model->property_publish()->get();
        $created_propertyPublish =$created_model->property_log_activities()->get();

        $created_propertyPhotoCount = $created_model->property_photos()->count();
        $created_propertyFlyerCount = $created_model->property_flyers()->count();

        /**
         * check existence created model
         */
   
        /**
         * check existence created model
         */
        if($old_propertyLog->count() != $created_propertyLog->count()){
            return false;
        }
        
        if($old_propertyPublish->count() != $created_propertyPublish->count()){
            return false;
        }

        $photoCoutnt = 0;
        if($old_min_propertyPhotoCount > $old_max_propertyPhotoCount){
            $photoCoutnt = $old_min_propertyPhotoCount;
        }else{
            $photoCoutnt = $old_max_propertyPhotoCount;
        }

        if($photoCoutnt != $created_propertyPhotoCount){
            return false;
        }

        $flyerCoutnt = 0;
        if($old_min_propertyFlyerCount > $old_max_propertyFlyerCount){
            $flyerCoutnt = $old_min_propertyFlyerCount;
        }else{
            $flyerCoutnt = $old_max_propertyFlyerCount;
        }

        if($flyerCoutnt != $created_propertyFlyerCount){
            return false;
        }

        return true;
    }


}
