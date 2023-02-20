<?php

namespace App\Jobs;

use App\Models\Prova;
use App\Models\Questao;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Queue\SerializesModels;
use romanzipp\QueueMonitor\Traits\IsMonitored;

class Provas implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, IsMonitored;

    protected $url;
    protected $concurso_id;
    public $tries = 0;


    public function __construct($url, $concurso_id)
    {
        $this->url = $url;
        $this->concurso_id = $concurso_id;
    }


    public function handle()
    {
        try {
            $client = new Client();
            $response = $client->get($this->url, [
                'query' => [
                    'tipo' => 'OBJETIVA',
                ]
            ]);

            $statusCode = $response->getStatusCode();

            if ($statusCode == 200) {

                $data = json_decode($response->getBody(), true);
                $provas = $data['list'];

                foreach ($provas as $prova) {
                    $this->updateOrCreateProva($prova, $this->concurso_id);

                    if ($prova['questoesObjetivas']) {
                        foreach ($prova['questoesObjetivas'] as $questao) {
                            $this->updateOrCreateQuestao($questao, $this->concurso_id, $prova['nome']);
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            $this->job->fail($e);
            echo $e->getMessage() . PHP_EOL;
        }
    }

    public function middleware()
    {
        return [
            new RateLimited('prova')
        ];
    }

    protected function updateOrCreateProva($prova, $concurso_id)
    {
        $provaModel = Prova::where('nome', $prova['nome'])->where('concurso_id', $concurso_id)->first();

        if ($provaModel) {
            $provaModel->nome = $prova['nome'];
            $provaModel->concurso_id = $concurso_id;
            $provaModel->next_run  = Carbon::now()->addDays(5);
            $provaModel->save();
            echo "A prova - {$prova['nome']} Atualizada com Sucesso!" . PHP_EOL;
        } else {
            Prova::create([
                'ext_id' => $prova['id'],
                'nome' => $prova['nome'],
                'concurso_id' => $concurso_id,
                'next_run' => Carbon::now()->addDays(5)
            ]);
            echo "A prova - {$prova['nome']} Foi Criada com sucesso" . PHP_EOL;
        }
    }


    protected function updateOrCreateQuestao($questao, $concurso_id, $prova_nome)
    {

        try {
            $questaoModel = Questao::where('ext_id', $questao['id'])->first();

            if ($questaoModel) {
                $questaoModel->numero_questao_atual = $questao['numeroQuestao'];
                $questaoModel->prova_nome = $prova_nome;
                $questaoModel->concurso_id = $concurso_id;
                $questaoModel->save();
                echo "QUESTOES - {$questao['numeroQuestao']} Atualizada com Sucesso!" . PHP_EOL;

            } else {
                Questao::create([
                    'ext_id' => $questao['id'],
                    'prova_nome' => $prova_nome,
                    'numero_questao_atual' => $questao['numeroQuestao'],
                    'concurso_id' =>  $concurso_id
                ]);
                echo "Questoes - {$questao['numeroQuestao']} Criada com Sucesso!" . PHP_EOL;
            }
        } catch (\Exception $e) {
            $this->job->fail($e);
            echo $e->getMessage() . PHP_EOL;
        }
    }
}
