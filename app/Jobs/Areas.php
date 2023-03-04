<?php

namespace App\Jobs;

use App\Models\Area;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use romanzipp\QueueMonitor\Traits\IsMonitored;

class Areas implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use IsMonitored;

    protected $url;
    public $tries = 0;


    public function __construct($url)
    {
        $this->url = $url;
    }

    public function handle()
    {
        try {
            $client = new Client();
            $response = $client->get($this->url, [
                'headers' => getDefaultHeaders(),
                'cache' => false,
            ]);

            $statusCode = $response->getStatusCode();

            if ($statusCode == 200) {
                $data = json_decode($response->getBody(), true);
                $areas = $data['areas'];

                foreach ($areas as $area) {
                    $this->updateOrCreateArea($area);
                }
            }
            sleep(getDelay());
        } catch (\Exception $e) {
            $this->job->fail($e);
            echo $e->getMessage() . PHP_EOL;
        }
    }

    protected function updateOrCreateArea($area)
    {
    try {
        $areaModel = Area::firstOrCreate(
            ['ext_id' => $area['id']],
        );

        if ($areaModel->wasRecentlyCreated || $areaModel->next_run < Carbon::now()->toDateString()) {
            $areaModel->nome = $area['nome'];
            $areaModel->hierarquia = $area['hierarquia'];
            $areaModel->next_run = Carbon::now()->addDays(5);
            $areaModel->save();
            echo "Area - {$area['nome']} Atualizada com Sucesso!" . PHP_EOL;
        }
    } catch (\Exception $e) {
        $this->job->fail($e);
        echo $e->getMessage() . PHP_EOL;
    }
}
}
