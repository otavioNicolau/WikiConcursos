<?php

namespace App\Jobs;

use App\Models\banca;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use romanzipp\QueueMonitor\Traits\IsMonitored;

class Bancas implements ShouldQueue
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
                'query' => [
                    'formato' => 'OBJETIVA',
                    'universo' => ''
                ]
            ]);

            $statusCode = $response->getStatusCode();

            if ($statusCode == 200) {
                $data = json_decode($response->getBody(), true);
                $bancas = $data['bancas'];

                foreach ($bancas as $banca) {
                    $this->updateOrCreateBanca($banca);
                }
            }
        } catch (\Exception $e) {
            $this->job->fail($e);
            echo $e->getMessage() . PHP_EOL;
        }
    }

    protected function updateOrCreateBanca($banca)
    {
        $bancaModel = banca::where('ext_id', $banca['id'])->first();

        if ($bancaModel) {
            if ($bancaModel->next_run < Carbon::now()) {
                $bancaModel->nome = $banca['nome'];
                $bancaModel->sigla = $banca['sigla'];
                $bancaModel->url = $banca['url'];
                $bancaModel->next_run = Carbon::now()->addDays(5);
                $bancaModel->save();
                echo "Banca - {$banca['nome']} Atualizada com Sucesso!" . PHP_EOL;
            }
        } else {
            banca::create([
                'ext_id' => $banca['id'],
                'nome' => $banca['nome'],
                'sigla' => $banca['sigla'],
                'url' => $banca['url'],
                'next_run' => Carbon::now()->addDays(5)
            ]);
            echo "Banca - {$banca['nome']} Foi Criada com sucesso" . PHP_EOL;
        }
    }
}
