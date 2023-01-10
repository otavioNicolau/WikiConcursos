<?php

namespace App\Jobs;

use App\Models\Profissao;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use romanzipp\QueueMonitor\Traits\IsMonitored;

class Profissoes implements ShouldQueue
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
                $profissoes = $data['profissoes'];

                foreach ($profissoes as $profissao) {
                    $this->updateOrCreateProfissao($profissao);
                }
            }
        } catch (\Exception $e) {
            $this->job->fail($e);
            echo $e->getMessage() . PHP_EOL;
        }
    }

    protected function updateOrCreateProfissao($profissao)
    {
        $profissaoModel = Profissao::where('ext_id', $profissao['id'])->first();

        if ($profissaoModel) {
            $profissaoModel->nome = $profissao['nome'];
            $profissaoModel->save();
            echo "Profissão - {$profissao['nome']} Atualizada com Sucesso!" . PHP_EOL;
        } else {
            Profissao::create([
                'ext_id' => $profissao['id'],
                'nome' => $profissao['nome'],
            ]);
            echo "Profissão - {$profissao['nome']} Foi Criada com sucesso" . PHP_EOL;
        }
    }
}
