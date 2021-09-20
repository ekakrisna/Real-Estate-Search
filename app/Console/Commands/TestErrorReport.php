<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

use App\Models\Customer;
use App\Models\CustomerNew;

use SendGrid\Mail\Mail;
use SendGrid\Mail\From;
use SendGrid\Mail\To;
use SendGrid\Mail\SendAt;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class TestErrorReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:errorreport';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test error report';

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
     * Send emails not sent yet.
     *
     * @return mixed
     */
    public function handle()
    {
        sendMessageOfErrorReport(
            "Test error report"
        );
    }
}
