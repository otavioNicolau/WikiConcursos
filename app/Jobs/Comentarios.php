<?php

namespace App\Jobs;

use App\Models\Cargo;
use App\Models\Comentario;
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
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, IsMonitored;

    protected $url;
    protected $id_questao;
    //public $tries = 0;
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
                'headers' => $this->headers,
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
        $comentarioModel = Comentario::where('id_questao', $id_questao)->first();


        $data_publicacao_comentario = DateTime::createFromFormat('Y-m-d H:i:s',  $comentario['dataPublicacaoComentario']);
        $data_publicacao_comentario = $data_publicacao_comentario->format('Y-m-d H:i:s');

        if ($comentarioModel) {
            $comentarioModel->comentario = $comentario['textoComentario'];
            $comentarioModel->data_publicacao_comentario = $data_publicacao_comentario;
            $comentarioModel->save();
            echo "Comentario da Questão - {$id_questao} Atualizada com Sucesso!" . PHP_EOL;
        } else {
            Comentario::create([
                'comentario' => $comentario['textoComentario'],
                'data_publicacao_comentario' => $data_publicacao_comentario,
                'id_questao' => $id_questao,

            ]);
            echo "Comentario da Questão - {$id_questao} Foi Criada com sucesso" . PHP_EOL;
        }
    }
}
