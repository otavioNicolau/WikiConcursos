<?php

namespace App\Jobs;

use App\Models\Escolaridade;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use romanzipp\QueueMonitor\Traits\IsMonitored;

class Escolaridades implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, IsMonitored;



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
            $response = $client->get($this->url, []);

            $statusCode = $response->getStatusCode();

            if ($statusCode == 200) {

                $data = json_decode($response->getBody(), true);
                $escolaridades = $data['escolaridades'];

                foreach ($escolaridades as $escolaridade) {
                    $this->updateOrCreateEscolaridade($escolaridade);
                }
            }
        } catch (\Exception $e) {
            $this->job->fail($e);
            echo $e->getMessage() . PHP_EOL;
        }
    }

    protected function updateOrCreateEscolaridade($escolaridade)
    {
        $escolaridadeModel = Escolaridade::where('ext_id', $escolaridade['id'])->first();

        if ($escolaridadeModel) {
            $escolaridadeModel->nome = $escolaridade['nome'];
            $escolaridadeModel->save();
            echo "Escolaridade - {$escolaridade['nome']} Atualizada com Sucesso!" . PHP_EOL;
        } else {
            Escolaridade::create([
                'ext_id' => $escolaridade['id'],
                'nome' => $escolaridade['nome'],
            ]);
            echo "Escolaridade - {$escolaridade['nome']} Foi Criada com sucesso" . PHP_EOL;
        }
    }
}
