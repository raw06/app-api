<?php

namespace App\Console\Commands;

use App\Mail\WelcomeEmail;
use App\Services\Elasticsearch\ElasticsearchSearchService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:es-questions';

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
     */
    public function handle(ElasticsearchSearchService $elasticsearchSearchService)
    {
        $elasticsearchSearchService->test();
    }
}
