<?php

namespace App\Jobs;

use App\Models\Alternativa;
use App\Models\Concurso;
use App\Models\ProvaQuestao;
use App\Models\Questao;
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

class Questoes implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use IsMonitored;

    protected $url;
    protected $data;
    protected $headers;
    //   public $tries = 0;


    public function __construct($url, $data, $headers)
    {
        $this->url = $url;
        $this->data = $data;
        $this->headers = $headers;
    }

    public function handle()
    {
        try {
            $client = new Client();
            $response = $client->get(
                $this->url,
                ['headers' => getDefaultHeaders()]
            );
            // $response = $client->post($this->url, ['headers' => $this->headers, 'form_params' => $this->data]);
            $statusCode = $response->getStatusCode();

            if ($statusCode == 200) {
                $data = json_decode($response->getBody(), true);

                $questao = $data['questao'];

                $this->updateOrCreateQuestao($questao);
                $this->updateOrCreateProvaQuestao($questao);
                $this->updateOrCreateConcurso($questao);

                $i = 0;
                foreach ($questao['alternativas'] as $alternativa) {
                    $this->updateOrCreateAlternativa($alternativa, $questao['idQuestao'], $i);
                    $i++;
                }
            }
            sleep(getDelayQuestoes());
        } catch (\Exception $e) {
        
            $this->job->fail($e);
            echo $e->getMessage() . PHP_EOL;
        }
    }

    public function middleware()
    {
        return [
            new RateLimited('questoes')
        ];
    }

    protected function updateOrCreateQuestao($questao)
    {
        try {
            $questaoModel = Questao::firstOrCreate(
                ['ext_id' => $questao['idQuestao']],
            );

            $enunciado1 = strip_tags($questao['enunciado'], '<img>');
            $enunciado = html_entity_decode($enunciado1, ENT_QUOTES, 'UTF-8');

            //  if ($questaoModel->wasRecentlyCreated) {
            $questaoModel->nome_assunto = isset($questao['nomeAssunto']) ? $questao['nomeAssunto'] : null;
            $questaoModel->enunciado = $enunciado;
            $questaoModel->id_materia = isset($questao['idMateria']) ? $questao['idMateria'] : null;
            //$questaoModel->correcao_questao = isset($questao['correcaoQuestao']) ? $questao['correcaoQuestao'] : null;
            $questaoModel->numero_alternativa_correta = isset($questao['numeroAlternativaCorreta']) ? $questao['numeroAlternativaCorreta'] : null;
            $questaoModel->possui_comentario = isset($questao['possuiComentario']) ? $questao['possuiComentario'] : null;
            $questaoModel->anulada = isset($questao['anulada']) ? $questao['anulada'] : null;
            $questaoModel->tipo_questao = isset($questao['tipoQuestao']) ? $questao['tipoQuestao'] : null;
            $questaoModel->desatualizada = isset($questao['desatualizada']) ? $questao['desatualizada'] : null;
            $questaoModel->formato_questao = isset($questao['formatoQuestao']) ? $questao['formatoQuestao'] : null;
            $questaoModel->data_atual = isset($questao['dataAtual']) ? $questao['dataAtual'] : null;
            $questaoModel->gabarito_preliminar = isset($questao['gabaritoPreliminar']) ? $questao['gabaritoPreliminar'] : null;
            $questaoModel->desatualizada_com_gabarito_preliminar = isset($questao['desatualizadaComGabaritoPreliminar']) ? $questao['desatualizadaComGabaritoPreliminar'] : null;
            $questaoModel->desatualizada_com_gabarito_definivo = isset($questao['desatualizadaComGabaritoDefinivo']) ? $questao['desatualizadaComGabaritoDefinivo'] : null;
            $questaoModel->questao_oculta = isset($questao['questaoOculta']) ? $questao['questaoOculta'] : null;
            $questaoModel->data_publicacao = isset($questao['dataPublicacao']['$']) ? $questao['dataPublicacao']['$'] : null;
            $questaoModel->next_run = Carbon::now()->addDays(5);
            $questaoModel->save();
            echo "QUESTÃO - {$questao['idQuestao']} Atualizada com Sucesso!" . PHP_EOL;
            // }
        } catch (\Exception $e) {
            $this->job->fail($e);
            echo $e->getMessage() . PHP_EOL;
        }
    }

    protected function updateOrCreateProvaQuestao($questao)
    {
        try {
            ProvaQuestao::firstOrCreate(
                [
                    'concurso_id' => $questao['concursoId'],  //ok
                    'numero_questao' => $questao['numeroQuestaoAtual'], //ok
                    'questao_id' => $questao['idQuestao'], // ok
                ],
                [
                    'cargo_nome' => $questao['cargoSigla'],
                    ]
            );

            echo "A ProvaQuestao - {$questao['cargoSigla']} Atualizada com Sucesso!" . PHP_EOL;
        } catch (\Exception $e) {
            $this->job->fail($e);
            echo $e->getMessage() . PHP_EOL;
        }
    }

    protected function updateOrCreateAlternativa($alternativa, $id_questao, $indice)
    {
        try {
            $alternativaModel = Alternativa::firstOrCreate(
                [
                    'ext_id' => $indice,
                    'id_questao' =>  $id_questao,
                ],
            );

            //if ($alternativaModel->wasRecentlyCreated) {
            $alternativa_sem_tags = strip_tags($alternativa, '<img>');
            $alternativa_decoded = html_entity_decode($alternativa_sem_tags, ENT_QUOTES, 'UTF-8');
            $alternativaModel->alternativa = $alternativa_decoded;
            $alternativaModel->save();
            echo "Alternativa - {$alternativa_decoded} Atualizada com Sucesso!" . PHP_EOL;
            // }
        } catch (\Exception $e) {
            $this->job->fail($e);
            echo $e->getMessage() . PHP_EOL;
        }
    }



    protected function updateOrCreateConcurso($questao)
    {
        try {
            $concursoModel = Concurso::firstOrCreate(
                ['ext_id' => $questao['concursoId']],
            );

            $concursoModel->area = isset($questao['concursoArea']) ? $questao['concursoArea'] : null;
            $concursoModel->especialidade = isset($questao['concursoEspecialidade']) ? $questao['concursoEspecialidade'] : null;
            $concursoModel->save();
            echo "CONCURSO - {$questao['concursoId']} Atualizado com Sucesso!" . PHP_EOL;
        } catch (\Exception $e) {
            $this->job->fail($e);
            echo $e->getMessage() . PHP_EOL;
        }
    }
}
