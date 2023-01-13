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
    public $tries = 0;


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



    protected function updateOrCreateQuestao($questoes)
    {

        try {
            $questaoModel = Questao::where('ext_id', $questoes['idQuestao'])->first();

            $data_atual = DateTime::createFromFormat('Y-m-d H:i:s',  $questoes['dataAtual']);
            $data_atual = $data_atual->format('Y-m-d H:i:s');

            $data_publicacao = DateTime::createFromFormat('Y-m-d H:i:s',  $questoes['dataPublicacao']);
            $data_publicacao = $data_publicacao->format('Y-m-d H:i:s');

            if ($questaoModel) {
                $questaoModel->id_materia = isset($questoes['idMateria']) ? $questoes['idMateria'] : null;
                $questaoModel->capitulo = isset($questoes['capitulo']) ? $questoes['capitulo'] : null;
                $questaoModel->id_assunto_anexo_capitulo = isset($questoes['idAssuntoAnexoCapitulo']) ? $questoes['idAssuntoAnexoCapitulo'] : null;
                $questaoModel->concurso_id = isset($questoes['concursoId']) ? $questoes['concursoId'] : null;
                $questaoModel->numero_questao_atual = isset($questoes['numeroQuestaoAtual']) ? $questoes['numeroQuestaoAtual'] : null;
                $questaoModel->enunciado = $questoes['enunciado'];
                $questaoModel->correcao_questao = isset($questoes['correcaoQuestao']) ? $questoes['correcaoQuestao'] : null;
                $questaoModel->numero_alternativa_correta = isset($questoes['numeroAlternativaCorreta']) ? $questoes['numeroAlternativaCorreta'] : null;
                $questaoModel->possui_comentario = isset($questoes['possuiComentario']) ? $questoes['possuiComentario'] : null;
                $questaoModel->anulada = isset($questoes['anulada']) ? $questoes['anulada'] : null;
                $questaoModel->tipo_questao = isset($questoes['tipoQuestao']) ? $questoes['tipoQuestao'] : null;
                $questaoModel->desatualizada = isset($questoes['desatualizada']) ? $questoes['desatualizada'] : null;
                $questaoModel->formato_questao = isset($questoes['formatoQuestao']) ? $questoes['formatoQuestao'] : null;
                $questaoModel->data_atual = $data_atual;
                $questaoModel->gabarito_preliminar = isset($questoes['gabaritoPreliminar']) ? $questoes['gabaritoPreliminar'] : null;
                $questaoModel->desatualizada_com_gabarito_preliminar = isset($questoes['desatualizadaComGabaritoPreliminar']) ? $questoes['desatualizadaComGabaritoPreliminar'] : null;
                $questaoModel->desatualizada_com_gabarito_definivo = isset($questoes['desatualizadaComGabaritoDefinivo']) ? $questoes['desatualizadaComGabaritoDefinivo'] : null;
                $questaoModel->questao_oculta = isset($questoes['questaoOculta']) ? $questoes['questaoOculta'] : null;
                $questaoModel->data_publicacao = $data_publicacao;
                $questaoModel->save();
                echo "QUESTOES - {$questoes['numeroQuestaoAtual']} Atualizada com Sucesso!" . PHP_EOL;
            } else {
                Questao::create([
                    'ext_id' => $questoes['idQuestao'],
                    'id_materia' => isset($questoes['idMateria']) ? $questoes['idMateria'] : null,
                    'capitulo' => isset($questoes['capitulo']) ? $questoes['capitulo'] : null,
                    'id_assunto_anexo_capitulo' => isset($questoes['idAssuntoAnexoCapitulo']) ? $questoes['idAssuntoAnexoCapitulo'] : null,
                    'concurso_id' => isset($questoes['concursoId']) ? $questoes['concursoId'] : null,
                    'numero_questao_atual' => isset($questoes['numeroQuestaoAtual']) ? $questoes['numeroQuestaoAtual'] : null,
                    'enunciado' => isset($questoes['enunciado']) ? $questoes['enunciado'] : null,
                    'correcao_questao' => isset($questoes['correcaoQuestao']) ? $questoes['correcaoQuestao'] : null,
                    'numero_alternativa_correta' => isset($questoes['numeroAlternativaCorreta']) ? $questoes['numeroAlternativaCorreta'] : null,
                    'possui_comentario' => isset($questoes['possuiComentario']) ? $questoes['possuiComentario'] : null,
                    'anulada' => isset($questoes['anulada']) ? $questoes['anulada'] : null,
                    'tipo_questao' => isset($questoes['tipoQuestao']) ? $questoes['tipoQuestao'] : null,
                    'desatualizada' => isset($questoes['desatualizada']) ? $questoes['desatualizada'] : null,
                    'formato_questao' => isset($questoes['formatoQuestao']) ? $questoes['formatoQuestao'] : null,
                    'data_atual' => $data_atual,
                    'gabarito_preliminar' => isset($questoes['gabaritoPreliminar']) ? $questoes['gabaritoPreliminar'] : null,
                    'desatualizada_com_gabarito_preliminar' => isset($questoes['desatualizadaComGabaritoPreliminar']) ? $questoes['desatualizadaComGabaritoPreliminar'] : null,
                    'desatualizada_com_gabarito_definivo' => isset($questoes['desatualizadaComGabaritoDefinivo']) ? $questoes['desatualizadaComGabaritoDefinivo'] : null,
                    'questao_oculta' => isset($questoes['questaoOculta']) ? $questoes['questaoOculta'] : null,
                    'data_publicacao' => $data_publicacao,
                ]);
                echo "Questoes - {$questoes['numeroQuestaoAtual']} Criada com Sucesso!" . PHP_EOL;
            }
        } catch (\Exception $e) {
            $this->job->fail($e);
            echo $e->getMessage() . PHP_EOL;
        }
    }

    protected function updateOrCreateAlternativa($alternativa, $id_questao, $indice)
    { //  'ext_id', 'id_questao', 'alternativa'
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
