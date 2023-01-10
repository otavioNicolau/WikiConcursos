<?php

namespace App\Jobs;

use App\Models\Concurso;
use App\Models\Edital;
use App\Models\Materia;
use DateTime;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use romanzipp\QueueMonitor\Traits\IsMonitored;

class Editais implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, IsMonitored;

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
                    //foreach ($item['edital'] as $edital) {
                    $this->updateOrCreateEdital($item['edital']);
                    // }
                    if ($item['concursos']) {

                        foreach ($item['concursos'] as $concurso) {

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

                            $this->updateOrCreateConcurso($concurso);
                        }
                    }
                }
            }

            $totalPages = $data['totalPages'];

            if ($this->num == 1) {
                for ($i = 2; $i <= $totalPages; $i++) {
                    dispatch(new Editais($this->url, $i));
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
            new RateLimited('cargos')
        ];
    }

    protected function updateOrCreateEdital($edital)
    {
        $editalModel = Edital::where('ext_id', $edital['id'])->first();

        $prazo_inscricao = DateTime::createFromFormat('d/m/Y H:i:s',  $edital['prazoInscricao']);
        $prazo_inscricao = $prazo_inscricao->format('Y-m-d H:i:s');

        $data_inclusao = DateTime::createFromFormat('d/m/Y H:i:s',  $edital['dataInclusao']);
        $data_inclusao = $data_inclusao->format('Y-m-d H:i:s');

        if ($editalModel) {
            $editalModel->nome = isset($edital['nome']) ? $edital['nome'] : null;
            $editalModel->prazo_inscricao = $prazo_inscricao;
            $editalModel->ano = isset($edital['ano']) ? $edital['ano'] : null;
            $editalModel->data_inclusao = $data_inclusao;
            $editalModel->vagas =  isset($edital['vagas']) ? $edital['vagas'] : null;
            $editalModel->salario_inicial_de = isset($edital['salarioInicialDe']) ? $edital['salarioInicialDe'] : null;
            $editalModel->salario_inicial_ate =  isset($edital['salarioInicialAte']) ? $edital['salarioInicialAte'] : null;
            $editalModel->taxa_inscricao_de =  isset($edital['taxaInscricaoDe']) ? $edital['taxaInscricaoDe'] : null;
            $editalModel->taxa_inscricao_ate =  isset($edital['taxaInscricaoAte']) ? $edital['taxaInscricaoAte'] : null;
            $editalModel->pagina_concurso =  isset($edital['paginaConcurso']) ? $edital['paginaConcurso'] : null;
            $editalModel->url = isset($edital['url']) ? $edital['url'] : null;
            $editalModel->publicado = isset($edital['publicado']) ? $edital['publicado'] : null;
            $editalModel->ficticio = isset($edital['ficticio']) ? $edital['ficticio'] : null;
            $editalModel->cargo_nome = isset($edital['cargoNome']) ? $edital['cargoNome'] : null;
            $editalModel->cargo_sigla = isset($edital['cargoSigla']) ? $edital['cargoSigla'] : null;
            $editalModel->save();
            echo "EDITAL - {$edital['nome']} Atualizada com Sucesso!" . PHP_EOL;
        } else {
            Edital::create([
                'ext_id' => $edital['id'],
                'nome' => isset($edital['nome']) ? $edital['nome'] : null,
                'id_banca' => $edital['idBanca'],
                'id_orgao' => $edital['idOrgao'],
                'prazo_inscricao' => $prazo_inscricao,
                'ano' => isset($edital['ano']) ? $edital['ano'] : null,
                'data_inclusao' => $data_inclusao,
                'vagas' => isset($edital['vagas']) ? $edital['vagas'] : null,
                'salario_inicial_de' => isset($edital['salarioInicialDe']) ? $edital['salarioInicialDe'] : null,
                'salario_inicial_ate' => isset($edital['salarioInicialAte']) ? $edital['salarioInicialAte'] : null,
                'taxa_inscricao_de' => isset($edital['salarioInicialAte']) ? $edital['taxaInscricaoDe'] : null,
                'taxa_inscricao_ate' => isset($edital['taxaInscricaoAte']) ? $edital['taxaInscricaoAte'] : null,
                'pagina_concurso' => isset($edital['paginaConcurso']) ? $edital['paginaConcurso'] : null,
                'url' =>  isset($edital['url']) ? $edital['url'] : null,
                'publicado' => isset($edital['publicado']) ? $edital['publicado'] : null,
                'ficticio' => isset($edital['ficticio']) ? $edital['ficticio'] : null,
                'cargo_nome' => isset($edital['cargoNome']) ? $edital['cargoNome'] : null,
                'cargo_sigla' => isset($edital['cargoSigla']) ? $edital['cargoSigla'] : null,
            ]);
            echo "EDITAL - {$edital['nome']} Foi Criada com sucesso" . PHP_EOL;
        }
    }

    protected function updateOrCreateConcurso($concurso)
    {
        $concursoModel = Concurso::where('ext_id', $concurso['concursoId'])->first();

        $data_aplicacao = DateTime::createFromFormat('d/m/Y H:i:s',  $concurso['dataAplicacao']);
        $data_aplicacao = $data_aplicacao->format('Y-m-d H:i:s');

        if ($concursoModel) {
            $concursoModel->data_aplicacao = $data_aplicacao;
            $concursoModel->escolaridade_enum = isset($concurso['escolaridadeEnum']) ? $concurso['escolaridadeEnum'] : null;
            $concursoModel->arquivo_gabarito = isset($concurso['arquivoGabarito']) ? $concurso['arquivoGabarito'] : null;
            $concursoModel->arquivo_discursiva = isset($concurso['arquivoDiscursiva']) ? $concurso['arquivoDiscursiva'] : null;
            $concursoModel->arquivo_objetiva = isset($concurso['arquivoObjetiva']) ? $concurso['arquivoObjetiva'] : null;
            $concursoModel->arquivo_edital = isset($concurso['arquivoEdital']) ? $concurso['arquivoEdital'] : null;
            $concursoModel->nome_completo = isset($concurso['nomeCompleto']) ? $concurso['nomeCompleto'] : null;
            $concursoModel->url_concurso = isset($concurso['urlConcurso']) ? $concurso['urlConcurso'] : null;
            $concursoModel->save();
            echo "CONCURSO - {$concurso['nomeCompleto']} Atualizada com Sucesso!" . PHP_EOL;
        } else {

            Concurso::create([
                'ext_id' => $concurso['concursoId'],
                'edital_id' => $concurso['editalId'],
                'data_aplicacao' => $data_aplicacao,
                'escolaridade_enum' => isset($concurso['escolaridadeEnum']) ? $concurso['escolaridadeEnum'] : null,
                'arquivo_gabarito' => isset($concurso['arquivoGabarito']) ? $concurso['arquivoGabarito'] : null,
                'arquivo_discursiva' => isset($concurso['arquivoDiscursiva']) ? $concurso['arquivoDiscursiva'] : null,
                'arquivo_objetiva' => isset($concurso['arquivoObjetiva']) ? $concurso['arquivoObjetiva'] : null,
                'arquivo_edital' => isset($concurso['arquivoEdital']) ? $concurso['arquivoEdital'] : null,
                'nome_completo' => isset($concurso['nomeCompleto']) ? $concurso['nomeCompleto'] : null,
                'url_concurso' => isset($concurso['urlConcurso']) ? $concurso['urlConcurso'] : null,
            ]);
            echo "CONCURSO - {$concurso['nomeCompleto']} Foi Criada com sucesso" . PHP_EOL;
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
                $fileType = $response->getHeader('Content-Type');
                $extension = '';
                if ($fileType[0] == "application/pdf") {
                    $extension = ".pdf";
                } else if ($fileType[0] == "image/jpeg" || $fileType[0] == "image/pjpeg" || $fileType[0] == "image/jpeg" || $fileType[0] == "image/pjpeg") {
                    $extension = ".jpg";
                } else if ($fileType[0] == "image/png") {
                    $extension = ".png";
                } else if ($fileType == "image/gif") {
                    $extension = ".gif";
                } else if ($fileType[0] == "application/x-zip-compressed") {
                    $extension = ".zip";
                } else if ($fileType[0] == "application/zip") {
                    $extension = ".zip";
                } else if ($fileType[0] == "application/rar") {
                    $extension = ".rar";
                } else if ($fileType[0] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document") {
                    $extension = ".docx";
                } else if ($fileType[0] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
                    $extension = ".xlsx";
                } else if ($fileType[0] == "application/vnd.openxmlformats-officedocument.presentationml.presentation") {
                    $extension = ".pptx";
                } else {
                    $extension = ".jpg";
                }

                $fileName = basename($file_url) . $extension;

                if (!Storage::exists($path . "/" . $fileName) || Storage::size($path . "/" . $fileName) !=  $fileLengthWeb[0]) {
                    Storage::disk('local')->put($path . "/" . $fileName, $fileContents);
                }
            }
        } catch (\Exception $e) {
            $this->job->fail($e);
            echo $e->getMessage() . PHP_EOL;
        }
    }
}
