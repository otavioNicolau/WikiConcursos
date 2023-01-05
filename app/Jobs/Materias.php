<?php

namespace App\Jobs;

use App\Models\Materia;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Materias implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $url;
    protected $num;

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
                ]
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
                    dispatch(new Materias($this->url, $i));
                }
            }
        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }

    protected function updateOrCreateMateria($materia)
    {
        $materiaModel = Materia::where('ext_id', $materia['id'])->first();

        if ($materiaModel) {
            $materiaModel->nome = $materia['nome'];
            $materiaModel->url = $materia['url'];
            $materiaModel->save();
            echo "MATERIA - {$materia['nome']} Atualizada com Sucesso!" . PHP_EOL;
        } else {
            Materia::create([
                'ext_id' => $materia['id'],
                'nome' => $materia['nome'],
                'url' => $materia['url'],
            ]);
            echo "MATERIA - {$materia['nome']} Foi Criada com sucesso" . PHP_EOL;
        }
    }
}
