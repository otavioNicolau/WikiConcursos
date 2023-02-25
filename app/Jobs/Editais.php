<?php

namespace App\Jobs;

use App\Models\Concurso;
use App\Models\Edital;
use App\Models\Materia;
use App\Models\Orgao;
use Carbon\Carbon;
use DateTime;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use romanzipp\QueueMonitor\Traits\IsMonitored;

class Editais implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use IsMonitored;

    protected $url;
    protected $num;
    public $tries = 0;


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
                    'busca.pagina' => $this->num
                ]
            ]);

            $statusCode = $response->getStatusCode();

            if ($statusCode == 200) {
                $data = json_decode($response->getBody(), true);
                $list = $data['list'];


                foreach ($list as $item) {
                    $this->updateOrCreateEdital($item['edital']);
                    $this->updateOrCreateOrgao($item['edital']);

                    if ($item['concursos']) {
                        foreach ($item['concursos'] as $concurso) {
                            $this->updateOrCreateConcurso($concurso);
                        }
                    }
                }
            }

            $totalPages = $data['totalPages'];

            if ($this->num == 1) {
                for ($i = 2; $i <= $totalPages; $i++) {
                    $job = new Editais($this->url, $i);
                    $job->onQueue('editais');
                    dispatch($job);
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
            new RateLimited('editais')
        ];
    }


    protected function updateOrCreateEdital($edital)
    {
        try {
            $prazo_inscricao = DateTime::createFromFormat('d/m/Y H:i:s', $edital['prazoInscricao']);
            $prazo_inscricao = $prazo_inscricao->format('Y-m-d H:i:s');
            $data_inclusao = DateTime::createFromFormat('d/m/Y H:i:s', $edital['dataInclusao']);
            $data_inclusao = $data_inclusao->format('Y-m-d H:i:s');

            $editalModel = Edital::firstOrCreate(
                ['ext_id' => $edital['id']],
                [
                'id_banca' => $edital['idBanca'],
                'id_orgao' => $edital['idOrgao'],
                ]
            );


            if ($editalModel->wasRecentlyCreated || $editalModel->next_run < Carbon::now()->toDateString()) {
                $editalModel->nome = isset($edital['nome']) ? $edital['nome'] : null;
                $editalModel->prazo_inscricao = $prazo_inscricao;
                $editalModel->ano = isset($edital['ano']) ? $edital['ano'] : null;
                $editalModel->data_inclusao = $data_inclusao;
                $editalModel->vagas = isset($edital['vagas']) ? $edital['vagas'] : null;
                $editalModel->salario_inicial_de = isset($edital['salarioInicialDe']) ? $edital['salarioInicialDe'] : null;
                $editalModel->salario_inicial_ate = isset($edital['salarioInicialAte']) ? $edital['salarioInicialAte'] : null;
                $editalModel->taxa_inscricao_de = isset($edital['taxaInscricaoDe']) ? $edital['taxaInscricaoDe'] : null;
                $editalModel->taxa_inscricao_ate = isset($edital['taxaInscricaoAte']) ? $edital['taxaInscricaoAte'] : null;
                $editalModel->pagina_concurso = isset($edital['paginaConcurso']) ? $edital['paginaConcurso'] : null;
                $editalModel->url = isset($edital['url']) ? $edital['url'] : null;
                $editalModel->publicado = isset($edital['publicado']) ? $edital['publicado'] : null;
                $editalModel->ficticio = isset($edital['ficticio']) ? $edital['ficticio'] : null;
                $editalModel->cargo_nome = isset($edital['cargoNome']) ? $edital['cargoNome'] : null;
                $editalModel->cargo_sigla = isset($edital['cargoSigla']) ? $edital['cargoSigla'] : null;
                $editalModel->next_run = Carbon::now()->addDays(5);
                $editalModel->save();

                echo "EDITAL - {$edital['nome']} Atualizada com Sucesso!" . PHP_EOL;
            }
        } catch (\Exception $e) {
            $this->job->fail($e);
            echo $e->getMessage() . PHP_EOL;
        }
    }

    protected function updateOrCreateConcurso($concurso)
    {
        try {
            $data_aplicacao = DateTime::createFromFormat('d/m/Y H:i:s', $concurso['dataAplicacao']);
            $data_aplicacao = $data_aplicacao->format('Y-m-d H:i:s');

            $concursoModel = Concurso::firstOrCreate(
                ['ext_id' => $concurso['concursoId']],
                ['edital_id' => $concurso['editalId']]
            );

            if ($concursoModel->wasRecentlyCreated || $concursoModel->next_run < Carbon::now()->toDateString()) {
                if (!empty($concurso['arquivoGabarito'])) {
                    $this->downloadFile(
                        "https://www.tecconcursos.com.br/download/" . $concurso['arquivoGabarito'],
                        "gabaritos"
                    );
                }

                if (!empty($concurso['arquivoDiscursiva'])) {
                    $this->downloadFile(
                        "https://www.tecconcursos.com.br/download/" . $concurso['arquivoDiscursiva'],
                        "discursivas"
                    );
                }

                if (!empty($concurso['arquivoObjetiva'])) {
                    $this->downloadFile(
                        "https://www.tecconcursos.com.br/download/" . $concurso['arquivoObjetiva'],
                        "objetivas"
                    );
                }

                if (!empty($concurso['arquivoEdital'])) {
                    $this->downloadFile(
                        "https://www.tecconcursos.com.br/download/" . $concurso['arquivoEdital'],
                        "editais"
                    );
                }

                $concursoModel->data_aplicacao = $data_aplicacao;
                $concursoModel->escolaridade_enum = isset($concurso['escolaridadeEnum']) ? $concurso['escolaridadeEnum'] : null;
                $concursoModel->arquivo_gabarito = isset($concurso['arquivoGabarito']) ? $concurso['arquivoGabarito'] : null;
                $concursoModel->arquivo_discursiva = isset($concurso['arquivoDiscursiva']) ? $concurso['arquivoDiscursiva'] : null;
                $concursoModel->arquivo_objetiva = isset($concurso['arquivoObjetiva']) ? $concurso['arquivoObjetiva'] : null;
                $concursoModel->arquivo_edital = isset($concurso['arquivoEdital']) ? $concurso['arquivoEdital'] : null;
                $concursoModel->nome_completo = isset($concurso['nomeCompleto']) ? $concurso['nomeCompleto'] : null;
                $concursoModel->url_concurso = isset($concurso['urlConcurso']) ? $concurso['urlConcurso'] : null;
                $concursoModel->next_run = Carbon::now()->addDays(5);
                $concursoModel->save();

                echo "CONCURSO - {$concurso['nomeCompleto']} Atualizada com Sucesso!" . PHP_EOL;
            }
        } catch (\Exception $e) {
            echo "Erro: " . $e->getMessage() . PHP_EOL;
        }
    }


    protected function updateOrCreateOrgao($edital)
    {
        try {
            Orgao::firstOrCreate(
                ['ext_id' => $edital['idOrgao']],
                ['orgao_regiao' => $edital['orgaoRegiao']],
            );
            echo "Orgão - {$edital['orgaoNome']} Atualizado com Sucesso!" . PHP_EOL;
        } catch (\Exception $e) {
            $this->job->fail($e);
            echo $e->getMessage() . PHP_EOL;
        }
    }


    protected function downloadFile($file_url, $path)
    {
        try {
            $client = new Client();
            $response = $client->get($file_url, []);

            if ($response->getStatusCode() == 200) {
                $fileContents = $response->getBody()->getContents();
                $fileLengthWeb = $response->getHeader('Content-Length');
                $fileName = basename($file_url);

                if (!Storage::disk('s3')->exists($path . "/" . $fileName) || Storage::disk('s3')->size($path . "/" . $fileName) !=  $fileLengthWeb[0]) {
                    Storage::disk('s3')->put($path . "/" . $fileName, $fileContents);
                }
            }
        } catch (\Exception $e) {
            $this->job->fail($e);
            echo $e->getMessage() . PHP_EOL;
        }
    }
}
