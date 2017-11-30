<?php

namespace App\Console\Commands;

use Elasticsearch\ClientBuilder;
use Illuminate\Console\Command;
use DB;

class ElasticImportCluster extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elastic:import-2-cluster';

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
            'https://blabla:528491@e3b25ff0ce2f8503b78c9bb23eb65054.us-east-1.aws.found.io:9243'
        ];
        $client = ClientBuilder::create()
            ->setHosts($hosts)
            ->build();
        $lastId = -1;
        $total = $calls = DB::table(DB::raw("stackdump.posts"))->count();
        $cnt = 0;
        while(true) {
            $calls = DB::table(DB::raw("stackdump.posts"))
                ->orderBy('Id', 'asc')
                ->where('Id', '>', $lastId)
                ->limit(500)
                ->get();
            if(count($calls) == 0) {
                break;
            }
            $params['body'] = [];
            foreach ($calls as $call) {
                $cnt++;
                $params['body'][] = [
                    'index' => [
                        '_index' => 'stackoverflow_posts_fixed',
                        '_id' => $call->Id,
                    ]
                ];
                $callArr = (array) $call;
                $params['body'][] = $callArr;
                $callArr['body']['BodyHtml'] = $callArr['body']['Body'];
//                $callArr['body']['Body'] = html
                echo "\r" . number_format((float) $cnt / $total * 100, 3, '.', '');
                $lastId = $call->Id;
            }
            $client->bulk($params);
        }
    }
}
