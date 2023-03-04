<?php

namespace App\Jobs;

use App\Models\Assunto;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use romanzipp\QueueMonitor\Traits\IsMonitored;

class Assuntos implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use IsMonitored;

    protected $url;
    protected $materiaID;
    public $tries = 0;


    public function __construct($url, $materiaID)
    {
        $this->url = $url;
        $this->materiaID = $materiaID;
    }

    public function handle()
    {
        try {
            $client = new Client();
            $response = $client->get($this->url, [
                'query' => [
                    'formato' => 'OBJETIVA',
                    'hierarquico' => false,
                    'materia' => $this->materiaID,
                    'universo' => ''
                ],'headers' => getDefaultHeaders(),
                'cache' => false
            ]);

            $statusCode = $response->getStatusCode();

            if ($statusCode == 200) {
                $data = json_decode($response->getBody(), true);
                $assuntos = $data['assuntos'];

                foreach ($assuntos as $assunto) {
                    $this->updateOrCreateAssunto($assunto);
                }
            }
            sleep(getDelayAssuntos());
        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }

    protected function updateOrCreateAssunto($assunto)
    {
        try {
            $assuntoModel = Assunto::firstOrCreate(
                ['ext_id' => $assunto['id']],
                [
                    'materia_id' => $assunto['materia']['id'],
                    ]
            );

            if ($assuntoModel->wasRecentlyCreated || $assuntoModel->next_run < Carbon::now()->toDateString()) {
                $assuntoModel->nome = $assunto['nome'];
                $assuntoModel->hierarquia = $assunto['hierarquia'];
                $assuntoModel->descendentes = $assunto['descendentes'];
                $assuntoModel->next_run = Carbon::now()->addDays(5);
                $assuntoModel->save();
                echo "ASSUNTO - {$assunto['nome']} Atualizada com Sucesso!" . PHP_EOL;
            }
        } catch (\Exception $e) {
            $this->job->fail($e);
            echo $e->getMessage() . PHP_EOL;
        }
    }
}
