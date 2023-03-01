<?php

namespace App\Jobs;

use App\Models\Materia;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use romanzipp\QueueMonitor\Traits\IsMonitored;

class Materias implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use IsMonitored;

    protected $url;
    protected $num;
    public $tries = 0;


    public function __construct($url, $num)
    {
        $this->url = $url;
        $this->num = $num;
    }

    public function handle()
    {
        try {
            $client = new Client();
            $response = $client->get($this->url, [
                'query' => [
                    'busca.ordenacao' => 'posicao',
                    'busca.pagina' => $this->num
                ],'headers' => getDefaultHeaders()
            ]);

            $statusCode = $response->getStatusCode();

            if ($statusCode == 200) {
                $data = json_decode($response->getBody(), true);
                $list = $data['list'];

                foreach ($list as $materia) {
                    $this->updateOrCreateMateria($materia);
                }
            }

            $totalPages = $data['totalPages'];

            if ($this->num == 1) {
                for ($i = 2; $i <= $totalPages; $i++) {
                    $job = new Materias($this->url, $i);
                    $job->onQueue('materias');
                    dispatch($job);

                }
            }
            sleep(getDelay());
        } catch (\Exception $e) {
            $this->job->fail($e);
            echo $e->getMessage() . PHP_EOL;
        }
    }

    protected function updateOrCreateMateria($materia)
    {
        try {
            $materiaModel = Materia::firstOrCreate(
                ['ext_id' => $materia['id']],
            );

            if ($materiaModel->wasRecentlyCreated || $materiaModel->next_run < Carbon::now()->toDateString()) {
                $materiaModel->nome = $materia['nome'];
                $materiaModel->url = $materia['url'];
                $materiaModel->next_run = Carbon::now()->addDays(5);
                $materiaModel->save();
                echo "MATERIA - {$materia['nome']} Atualizada com Sucesso!" . PHP_EOL;
            }
        } catch (\Exception $e) {
            $this->job->fail($e);
            echo $e->getMessage() . PHP_EOL;
        }
    }
}
