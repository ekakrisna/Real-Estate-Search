<?php

namespace App\Console\Scraping\DataRegister;

use App\Models\LpScraping as LpScrapingModel;
use App\Models\LpScrapingLog;
use App\Models\LpScrapingPublish;
use App\Models\LpProperty;
use App\Models\LpPropertyStatus;
use App\Models\LpPropertyPublish;
use App\Models\LpPropertyLogActivity;
use App\Models\LpPropertyScrapingType;
use App\Models\LpPropertyConvertStatus;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Console\Scraping\DataRegister\Base\BaseScrapingDataRegister;


class LpScrapingDataRegister extends BaseScrapingDataRegister
{
    protected $_scrapingOrm = LpScrapingModel::class;
    protected $_scrapingLogOrm = LpScrapingLog::class;
    protected $_scrapingPublishOrm = LpScrapingPublish::class;

    protected $_propertyOrm = LpProperty::class;
    protected $_propertyStatusOrm = LpPropertyStatus::class;
    protected $_propertyPublishOrm = LpPropertyPublish::class;
    protected $_propertyLogActivityhOrm = LpPropertyLogActivity::class;
    protected $_propertyScrapingTypeOrm = LpPropertyScrapingType::class;
    protected $_propertyConvertStatusOrm = LpPropertyConvertStatus::class;

    protected $is_insert_finish_status = false;
    protected $is_insert_news = false;
}