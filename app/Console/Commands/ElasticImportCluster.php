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
            'https://blabla:528491@8a9223fa8b39858aebf904fdc07929a4.ap-southeast-1.aws.found.io:9243'
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
                ->limit(200)
                ->get();
            if(count($calls) == 0) {
                break;
            }
            $params['body'] = [];
            foreach ($calls as $call) {
                $cnt++;
                $params['body'][] = [
                    'index' => [
                        '_index' => 'stackoverflow',
                        '_type' => 'posts',
                        '_id' => $call->Id,
                    ]
                ];
                $callArr = (array) $call;
                unset($callArr['Id']);
                $params['body'][] = $callArr;
                echo "\r" . number_format((float) $cnt / $total * 100, 3, '.', '');
                $lastId = $call->Id;
            }
            $client->bulk($params);
        }
    }
}
