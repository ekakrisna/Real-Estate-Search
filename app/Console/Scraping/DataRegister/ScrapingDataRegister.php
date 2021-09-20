<?php

namespace App\Console\Scraping\DataRegister;

use App\Models\Scraping as ScrapingModel;
use App\Models\ScrapingLog;
use App\Models\ScrapingPublish;
use App\Models\Property;
use App\Models\PropertyStatus;
use App\Models\PropertyPublish;
use App\Models\PropertyLogActivity;
use App\Models\PropertyScrapingType;
use App\Models\PropertyConvertStatus;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Console\Scraping\DataRegister\Base\BaseScrapingDataRegister;


class ScrapingDataRegister extends BaseScrapingDataRegister
{
    protected $_scrapingOrm = ScrapingModel::class;
    protected $_scrapingLogOrm = ScrapingLog::class;
    protected $_scrapingPublishOrm = ScrapingPublish::class;

    protected $_propertyOrm = Property::class;
    protected $_propertyStatusOrm = PropertyStatus::class;
    protected $_propertyPublishOrm = PropertyPublish::class;
    protected $_propertyLogActivityhOrm = PropertyLogActivity::class;
    protected $_propertyScrapingTypeOrm = PropertyScrapingType::class;
    protected $_propertyConvertStatusOrm = PropertyConvertStatus::class;

    protected $is_insert_finish_status = true;
    protected $is_insert_news = true;
    protected $is_add_condtion_for_back_up = true;
}