<?php

namespace App\Jobs;

use App\Models\Prova;
use App\Models\ProvaQuestao;
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
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use IsMonitored;

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
                ],'headers' => getDefaultHeaders()
            ]);

            $statusCode = $response->getStatusCode();

            if ($statusCode == 200) {
                $data = json_decode($response->getBody(), true);
                $provas = $data['list'];

                foreach ($provas as $prova) {
                    $this->updateOrCreateProva($prova, $this->concurso_id);

                    if ($prova['questoesObjetivas']) {
                        foreach ($prova['questoesObjetivas'] as $questao) {
                            $this->updateOrCreateProvaQuestao($questao, $this->concurso_id, $prova['nome']);
                            $this->updateOrCreateQuestao($questao, $this->concurso_id, $prova['nome']);
                        }
                    }
                }
            }

            sleep(getDelayProvas());
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


    protected function updateOrCreateProvaQuestao($questao, $concurso_id, $prova_nome)
    {
        try {
            $provaModel = ProvaQuestao::firstOrCreate(
                [
                    'prova_nome' => $prova_nome,
                    'concurso_id' => $concurso_id,
                    'numero_questao' => $questao['numeroQuestao'],
                    'questao_id' => $questao['id'],
                ],
            );

            echo "A ProvaQuestao - {$prova_nome} Atualizada com Sucesso!" . PHP_EOL;
        } catch (\Exception $e) {
            $this->job->fail($e);
            echo $e->getMessage() . PHP_EOL;
        }
    }


    protected function updateOrCreateProva($prova, $concurso_id)
    {
        try {
            $provaModel = Prova::firstOrCreate(
                [
                    'nome' => $prova['nome'],
                    'concurso_id' => $concurso_id,
                ],
                [
                    'ext_id' => $prova['id'],
                    ]
            );

            $provaModel->next_run  = Carbon::now()->addDays(5);
            $provaModel->save();
            echo "A prova - {$prova['nome']} Atualizada com Sucesso!" . PHP_EOL;
        } catch (\Exception $e) {
            $this->job->fail($e);
            echo $e->getMessage() . PHP_EOL;
        }
    }


    protected function updateOrCreateQuestao($questao, $concurso_id, $prova_nome)
    {
        try {
            Questao::firstOrCreate(
                ['ext_id' => $questao['id']],
            );
            echo "QUESTOES - {$questao['numeroQuestao']} Atualizada com Sucesso!" . PHP_EOL;
        } catch (\Exception $e) {
            $this->job->fail($e);
            echo $e->getMessage() . PHP_EOL;
        }
    }
}
