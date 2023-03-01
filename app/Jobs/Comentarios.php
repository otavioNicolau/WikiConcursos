<?php

namespace App\Jobs;

use App\Models\Cargo;
use App\Models\Comentario;
use Carbon\Carbon;
use DateTime;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Queue\SerializesModels;
use romanzipp\QueueMonitor\Traits\IsMonitored;

class Comentarios implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use IsMonitored;

    protected $url;
    protected $id_questao;
    public $tries = 0;
    public $headers;

    public function __construct($url, $id_questao, $headers)
    {
        $this->url = $url;
        $this->id_questao = $id_questao;
        $this->headers = $headers;
    }


    public function handle()
    {
        try {
            $client = new Client();
            $response = $client->get($this->url, [
                'headers' => getDefaultHeaders(),
                'query' => [
                    'tokenPreVisualizacao' => '',
                ]
            ]);

            $statusCode = $response->getStatusCode();

            if ($statusCode == 200) {
                $data = json_decode($response->getBody(), true);
                $comentario = $data['comentario'];
                $this->updateOrCreateComentario($comentario, $this->id_questao);
            }
            sleep(getDelayComentarios());
            
        } catch (\Exception $e) {
            $this->job->fail($e);
            echo $e->getMessage() . PHP_EOL;
        }
    }

    public function middleware()
    {
        return [
            new RateLimited('comentario')
        ];
    }

    protected function updateOrCreateComentario($comentario, $id_questao)
    {
        try {
            $comentarioModel = Comentario::firstOrCreate(
                ['id_questao' =>  $id_questao],
            );

            if ($comentarioModel->wasRecentlyCreated || $comentarioModel->next_run < Carbon::now()->toDateString()) {
                $comentarioModel->comentario = $comentario['textoComentario'];
                $comentarioModel->data_publicacao_comentario = $comentario['dataPublicacaoComentario'];
                $comentarioModel->next_run = Carbon::now()->addDays(5);
                $comentarioModel->save();
                echo "Comentario da Questão - {$id_questao} Atualizada com Sucesso!" . PHP_EOL;
            }
        } catch (\Exception $e) {
            $this->job->fail($e);
            echo $e->getMessage() . PHP_EOL;
        }
    }
}
