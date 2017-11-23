<?php

namespace App\Console\Commands;

use Elasticsearch\ClientBuilder;
use Illuminate\Console\Command;
use DB;

class ElasticScrollTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elastic:test-scroll';

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
        $hosts = [
            'https://blabla:528491@f51f1102ffdc02ec256f9c9ee4af21dd.us-east-1.aws.found.io:9243'
        ];
        $client = ClientBuilder::create()
            ->setHosts($hosts)
            ->build();
        $scrollKeepAlive = "1m";
        $params = [
            "scroll" => $scrollKeepAlive,
            "size" => 100,
            "index" => "stackoverflow_posts",
            "body" => [
                "query" => [
                    "bool" => [
                        "filter" => [
                            [
                                "range" => [
                                    "CreationDate" => [
                                        "gte" => "2014-09-17 21:05:50",
                                        "lte" => "2014-09-17 22:05:50"
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $response = $client->search($params);

        $cnt = 0;

        while (isset($response['hits']['hits']) && count($response['hits']['hits']) > 0) {

            $hits = $response['hits']['hits'];
//            print_r($source);
            foreach ($hits as $item) {
                $cnt++;
                echo "\r$cnt";
            }

            $scroll_id = $response['_scroll_id'];
            $response = $client->scroll([
                    "scroll_id" => $scroll_id,
                    "scroll" => $scrollKeepAlive
                ]
            );
        }
        echo "\nDone!\n";
    }
}
