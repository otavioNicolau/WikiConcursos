<?php

namespace App\Jobs;

use App\Models\Alternativa;
use App\Models\Questao;
use DateTime;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use romanzipp\QueueMonitor\Traits\IsMonitored;

class Questoes implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, IsMonitored;

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
            $response = $client->post($this->url, ['headers' => $this->headers, 'form_params' => $this->data]);

            $statusCode = $response->getStatusCode();

            if ($statusCode == 200) {

                $data = json_decode($response->getBody(), true);

                $questao = $data['questao'];

                $this->updateOrCreateQuestao($questao);

                $i = 0;
                foreach ($questao['alternativas'] as $alternativa) {
                    $this->updateOrCreateAlternativa($alternativa, $questao['idQuestao'], $i);
                    $i++;
                }
            }
        } catch (\Exception $e) {
            $this->job->fail($e);
            echo $e->getMessage() . PHP_EOL;
        }
    }



    protected function updateOrCreateQuestao($questao)
    {

        try {
            $questaoModel = Questao::where('ext_id', $questao['idQuestao'])->first();


            if ($questaoModel) {
                $questaoModel->id_materia = isset($questao['idMateria']) ? $questao['idMateria'] : null;
                $questaoModel->capitulo = isset($questao['capitulo']) ? $questao['capitulo'] : null;
                $questaoModel->id_assunto_anexo_capitulo = isset($questao['idAssuntoAnexoCapitulo']) ? $questao['idAssuntoAnexoCapitulo'] : null;
                $questaoModel->concurso_id = isset($questao['concursoId']) ? $questao['concursoId'] : null;
                $questaoModel->numero_questao_atual = isset($questao['numeroQuestaoAtual']) ? $questao['numeroQuestaoAtual'] : null;
                $questaoModel->enunciado = $questao['enunciado'];
                $questaoModel->correcao_questao = isset($questao['correcaoQuestao']) ? $questao['correcaoQuestao'] : null;
                $questaoModel->numero_alternativa_correta = isset($questao['numeroAlternativaCorreta']) ? $questao['numeroAlternativaCorreta'] : null;
                $questaoModel->possui_comentario = isset($questao['possuiComentario']) ? $questao['possuiComentario'] : null;
                $questaoModel->anulada = isset($questao['anulada']) ? $questao['anulada'] : null;
                $questaoModel->tipo_questao = isset($questao['tipoQuestao']) ? $questao['tipoQuestao'] : null;
                $questaoModel->desatualizada = isset($questao['desatualizada']) ? $questao['desatualizada'] : null;
                $questaoModel->formato_questao = isset($questao['formatoQuestao']) ? $questao['formatoQuestao'] : null;
                $questaoModel->data_atual = $questao['dataAtual'];
                $questaoModel->gabarito_preliminar = isset($questao['gabaritoPreliminar']) ? $questao['gabaritoPreliminar'] : null;
                $questaoModel->desatualizada_com_gabarito_preliminar = isset($questao['desatualizadaComGabaritoPreliminar']) ? $questao['desatualizadaComGabaritoPreliminar'] : null;
                $questaoModel->desatualizada_com_gabarito_definivo = isset($questao['desatualizadaComGabaritoDefinivo']) ? $questao['desatualizadaComGabaritoDefinivo'] : null;
                $questaoModel->questao_oculta = isset($questao['questaoOculta']) ? $questao['questaoOculta'] : null;
                $questaoModel->data_publicacao = $questao['dataPublicacao']['$'];
                $questaoModel->save();
                echo "QUESTOES - {$questao['numeroQuestaoAtual']} Atualizada com Sucesso!" . PHP_EOL;
            } else {
                Questao::create([
                    'ext_id' => $questao['idQuestao'],
                    'id_materia' => isset($questao['idMateria']) ? $questao['idMateria'] : null,
                    'capitulo' => isset($questao['capitulo']) ? $questao['capitulo'] : null,
                    'id_assunto_anexo_capitulo' => isset($questao['idAssuntoAnexoCapitulo']) ? $questao['idAssuntoAnexoCapitulo'] : null,
                    'concurso_id' => isset($questao['concursoId']) ? $questao['concursoId'] : null,
                    'numero_questao_atual' => isset($questao['numeroQuestaoAtual']) ? $questao['numeroQuestaoAtual'] : null,
                    'enunciado' => isset($questao['enunciado']) ? $questao['enunciado'] : null,
                    'correcao_questao' => isset($questao['correcaoQuestao']) ? $questao['correcaoQuestao'] : null,
                    'numero_alternativa_correta' => isset($questao['numeroAlternativaCorreta']) ? $questao['numeroAlternativaCorreta'] : null,
                    'possui_comentario' => isset($questao['possuiComentario']) ? $questao['possuiComentario'] : null,
                    'anulada' => isset($questao['anulada']) ? $questao['anulada'] : null,
                    'tipo_questao' => isset($questao['tipoQuestao']) ? $questao['tipoQuestao'] : null,
                    'desatualizada' => isset($questao['desatualizada']) ? $questao['desatualizada'] : null,
                    'formato_questao' => isset($questao['formatoQuestao']) ? $questao['formatoQuestao'] : null,
                    'data_atual' =>  $questao['dataAtual'],
                    'gabarito_preliminar' => isset($questao['gabaritoPreliminar']) ? $questao['gabaritoPreliminar'] : null,
                    'desatualizada_com_gabarito_preliminar' => isset($questao['desatualizadaComGabaritoPreliminar']) ? $questao['desatualizadaComGabaritoPreliminar'] : null,
                    'desatualizada_com_gabarito_definivo' => isset($questao['desatualizadaComGabaritoDefinivo']) ? $questao['desatualizadaComGabaritoDefinivo'] : null,
                    'questao_oculta' => isset($questao['questaoOculta']) ? $questao['questaoOculta'] : null,
                    'data_publicacao' => $questao['dataPublicacao']['$'],
                ]);
                echo "Questoes - {$questao['numeroQuestaoAtual']} Criada com Sucesso!" . PHP_EOL;
            }
        } catch (\Exception $e) {
            $this->job->fail($e);
            echo $e->getMessage() . PHP_EOL;
        }
    }

    protected function updateOrCreateAlternativa($alternativa, $id_questao, $indice)
    { 
        try {
            $alternativaModel = Alternativa::where('ext_id', $indice)
                ->where('questao_id', $id_questao)
                ->first();

            if ($alternativaModel) {
                $alternativaModel->alternativa = $alternativa[0];
                $alternativaModel->save();
                echo "Alternativa - {$alternativa[0]} Atualizada com Sucesso!" . PHP_EOL;
            } else {
                Alternativa::create([
                    'ext_id' => $indice,
                    'id_questao' => $id_questao,
                    'alternativa' => $alternativa[0],
                ]);
                echo "Alternativa - {$alternativa[0]} Foi Criada com sucesso" . PHP_EOL;
            }
        } catch (\Exception $e) {
            $this->job->fail($e);
            echo $e->getMessage() . PHP_EOL;
        }
    }
}
