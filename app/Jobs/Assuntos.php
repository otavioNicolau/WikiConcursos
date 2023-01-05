<?php

namespace App\Jobs;

use App\Models\Assunto;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Assuntos implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $url;
    protected $materiaID;

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
                ]
            ]);

            $statusCode = $response->getStatusCode();

            if ($statusCode == 200) {

                $data = json_decode($response->getBody(), true);
                $assuntos = $data['assuntos'];

                foreach ($assuntos as $assunto) {
                    $this->updateOrCreateAssunto($assunto);
                }
            }
        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }

    protected function updateOrCreateAssunto($assunto)
    {
        $assuntoModel = Assunto::where('ext_id', $assunto['id'])->first();

        if ($assuntoModel) {
            $assuntoModel->nome = $assunto['nome'];
            $assuntoModel->materia_id = $assunto['materia']['id'];
            $assuntoModel->hierarquia = $assunto['hierarquia'];
            $assuntoModel->descendentes = $assunto['descendentes'];
            $assuntoModel->save();
            echo "ASSUNTO - {$assunto['nome']} Atualizada com Sucesso!" . PHP_EOL;
        } else {
            Assunto::create([
                'ext_id' => $assunto['id'],
                'nome' => $assunto['nome'],
                'materia_id' => $assunto['materia']['id'],
                'hierarquia' => $assunto['hierarquia'],
                'descendentes' => $assunto['descendentes']
            ]);
            echo "ASSUNTO - {$assunto['nome']} Foi Criada com sucesso" . PHP_EOL;
        }
    }
}
