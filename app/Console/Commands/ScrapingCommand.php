<?php

namespace App\Console\Commands;

use App\Console\Scraping\DataRow\AtHomeDataRow;
use App\Console\Scraping\DataRow\SuumoDataRow;
use App\Console\Scraping\scraping;
use App\Console\Scraping\DataRegister\ScrapingDataRegister;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

use App\Models\Scraping as ScrapingModel;
use App\Models\ScrapingLog;
use App\Models\ScrapingPublish;

/**
 *
 * Relations effected data.
 *  - SuumoDataRow class
 *  - AtHomeDataRow class
 *  -
 */
class ScrapingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraping:getdata';

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

    // Scraping URL
    const BASE_URL = "https://dataapi.octoparse.com/";
    const URL_GET_TOKEN = "token";
    const URL_GET_GROUP = "api/TaskGroup";
    const URL_GET_TASK_LIST = "api/Task";
    const URL_GET_DATA = "api/alldata/GetDataOfTaskByOffset";
    const URL_DELETE_DATA = "api/task/RemoveDataByTaskId";

    /**
     * Scraping Site
     */
    const PUBLISHER_SUUMO = "SUUMO";
    const PUBLISHER_atHOME = "atHome";

    const PUBLISHER_SITES = [
        self::PUBLISHER_SUUMO,
        self::PUBLISHER_atHOME,
    ];

    const DATA_TYPE_LANDAREA = "土地情報";

    /**
     * Form method
     */
    const POST = "POST";
    const GET = "GET";
    const DATA_SIZE = 100;

    private $Access_Token = "";
    private $client;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ini_set("memory_limit", "-1");

        return Log::info("Execute scraping:getdata");
        sendMessageViaChatwork('Execute scraping:getdata');

        // create common web client
        $this->client = new \GuzzleHttp\Client([
            'base_uri' => self::BASE_URL,
            'curl' => [
                CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_CIPHER_LIST => 'AES256-SHA',
            ],
        ]);

        // get Access_Token
        $responseData = $this->getOctoparseToken();
        if ($responseData === "") {
            Log::info(Carbon::now() . "Can't get Access_Token for octoparse");
            return;
        }
        $this->Access_Token = $responseData->access_token;

        // Get Group List
        $groupList = $this->getGroupList();

        foreach ($groupList->data as $group) {
            $this->processOfOneGroup(
                $group->taskGroupId,
                $group->taskGroupName // publisher_name
            );
        }
    }

    private function processOfOneGroup($taskGroupId, $publisher_name)
    {
        // if publisher name is not found PUBLISHER_SITES
        if (!in_array($publisher_name, self::PUBLISHER_SITES)) {
            return;
        }
        // Get DataRow class instance.
        switch ($publisher_name) {
            case self::PUBLISHER_SUUMO:
                $scrapingDataRawClass = new SuumoDataRow();
                break;
            case self::PUBLISHER_SUUMO:
                $scrapingDataRawClass = new AtHomeDataRow();
                break;
            default:
                $scrapingDataRawClass = null;
                // * on PHP, "continue" will apply "switch" too.
        }
        if ($scrapingDataRawClass) {
            return;
        }

        // get Task List
        $taskList = $this->getTaskList($taskGroupId);
        foreach ($taskList->data as $task) {
            $this->processOfOneTask(
                $task->taskName,
                $scrapingDataRawClass,
                $publisher_name,
                $task->taskId
            );
        }
        return;
    }

    private function processOfOneTask($taskName, $scrapingDataRawClass, $publisher_name, $taskId)
    {
        // if not land information
        if (strpos($taskName, self::DATA_TYPE_LANDAREA) === false) {
            return;
        }

        $ScrapingDataRegister = new ScrapingDataRegister();

        // loop description
        $offset = 0;
        while ($offset >= 0) {
            // get Scraping Data for Octopers
            return $offset = $this->requestToGetScrapingDataForLoop(
                $scrapingDataRawClass,
                $ScrapingDataRegister,
                $publisher_name,
                $taskId,
                $offset
            );
            // If this method return -1, break this loop.
            // If not, continue to next offset.
        }

        $result = $ScrapingDataRegister->dataRegister();
        if (!$result) {
            Log::info("ERROR at data register funciton." . Carbon::now());
            if (!app()->isLocal()) {
                sendMessageViaChatworkScrapingReport($publisher_name, \config('chatwork.message_site-dataformat-is-changed'));
            }
            return;
        }
        // [If local testing] Remove octoparseData.
        if (!app()->isLocal()) {
            $this->removeOctparseData($taskId);
        }
    }

    /**
     * [About return value]
     * Please break loop if this method return -1.
     * If not, please call this method again and take over returned offset value.
     */
    private function requestToGetScrapingDataForLoop($scrapingDataRawClass, $ScrapingDataRegister, $publisher_name, $taskId, $offset)
    {
        // get Scraping Data for Octopers
        $response = $this->getScrapingData($taskId, $offset, self::DATA_SIZE);
        foreach ($response->data->dataList as $data) {
            // get result of execute fill
            $result = $scrapingDataRawClass->convertFromScrapingData($data);

            if (!$result) {
                Log::info("ERROR for at data convert funciton" . Carbon::now() . print_r($data, true));
                sendMessageViaChatworkScrapingReport($publisher_name, \config('chatwork.message_site-layout-is-changed'));
                continue;
            }
            $ScrapingDataRegister->addData($scrapingDataRawClass);
        }
        //Log::info("Error saveData2ScrapingTable".Carbon::now().print_r($scrapingDataRawClass, true));
        // If restTotal is 0 , Update octopers data status and loop flag change false
        if ($response->data->restTotal === 0) {
            return -1;
        } else {
            // offset increment
            $offset = $response->data->offset;
            $offset += self::DATA_SIZE;
            return $offset;
        }
    }

    /** *********************************************************************************
     * Requesting token methods.
     * [note] since it depend on $this->client, not static.
     * **********************************************************************************
     */

    /**
     * Use Octopare Api
     */
    private function getOctoparseToken()
    {
        $headers = [
            'Content-Type' => 'application/json,text/json',
        ];

        $body = [
            'username' => config('app.OCTOPARSE_USER'),
            'password' => config('app.OCTOPARSE_PASSWORD'),
            'grant_type' => 'password',
        ];

        try {
            return $this->request_api(self::URL_GET_TOKEN, self::POST, $headers, $body);
        } catch (\Exception $e) {
            return Log::info("Error getToken" . Carbon::now() . print_r($e, true));
            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            sendMessageOfErrorReport("ScrapingCommand (getToken Function)", $e);
            //------------------------------------------------------
        }
    }

    private function getGroupList()
    {
        $headers = [
            'Content-Type' => 'application/json,text/json',
            'Authorization' => 'bearer ' . $this->Access_Token,
        ];

        try {
            return $this->request_api(self::URL_GET_GROUP, self::GET, $headers);
        } catch (\Exception $e) {
            return Log::info("Error getGroupList" . Carbon::now() . print_r($e, true));

            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            sendMessageOfErrorReport("ScrapingCommand (getGroupList Function), ", $e);
            //------------------------------------------------------
        }
    }

    private function getTaskList($publisher_task_id)
    {
        $headers = [
            'Content-Type' => 'application/json,text/json',
            'Authorization' => 'bearer ' . $this->Access_Token,
        ];

        $url = self::URL_GET_TASK_LIST . '?taskGroupId=' . $publisher_task_id;

        try {
            return $this->request_api($url, self::GET, $headers);
        } catch (\Exception $e) {
            return Log::info("Error getTaskList" . Carbon::now() . print_r($e, true));

            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            sendMessageOfErrorReport("ScrapingCommand (getTaskList Function),", $e);
            //------------------------------------------------------
        }
    }

    private function getScrapingData($taskId, $offset, $size)
    {
        $headers = [
            'Content-Type' => 'application/json,text/json',
            'Authorization' => 'bearer ' . $this->Access_Token,
        ];

        $url = self::URL_GET_DATA . '?taskId=' . $taskId . '&offset=' . $offset . '&size=' . $size;
        try {
            return $this->request_api($url, self::GET, $headers);
        } catch (\Exception $e) {
            return Log::info("Error getScrapingData" . Carbon::now() . print_r($e, true));

            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            sendMessageOfErrorReport("ScrapingCommand (getScrapingData Function),", $e);
            //------------------------------------------------------
        }
    }

    private function removeOctparseData($taskId)
    {
        $headers = [
            'Content-Type' => 'application/json,text/json',
            'Authorization' => 'bearer ' . $this->Access_Token,
        ];

        $url = self::URL_DELETE_DATA . '?taskId=' . $taskId;
        try {
            $this->request_api($url, self::POST, $headers);
        } catch (\Exception $e) {
            return Log::info("Error removeOctparseData" . Carbon::now() . print_r($e, true));

            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            sendMessageOfErrorReport("ScrapingCommand (removeOctparseData Function),", $e);
            //------------------------------------------------------
        }
    }

    private function request_api($url, $method, $headers, $body = null)
    {
        try {
            $response = $this->client->request($method, $url, [
                'headers' => $headers,
                'form_params' => $body,
            ]);
            //dd($this->client);
        } catch (\Exception $e) {

            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            sendMessageOfErrorReport("ScrapingCommand (request_api Function), ", $e);
            //------------------------------------------------------

            throw new \Exception($e);
        }

        $response_body = (string) $response->getBody();
        $response_body = json_decode($response_body);
        return $response_body;
    }
}
