<?php

namespace App\Jobs;

use App\Models\Orgao;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Queue\SerializesModels;
use romanzipp\QueueMonitor\Traits\IsMonitored;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use PhpParser\Node\Stmt\Echo_;

class Orgaos implements ShouldQueue
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

    public function middleware()
    {
        return [
            new RateLimited('orgaos')
        ];
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

                foreach ($list as $orgao) {
                    $this->updateOrCreateOrgao($orgao);
                }
            }

            $totalPages = $data['totalPages'];

            if ($this->num == 1) {
                for ($i = 2; $i <= $totalPages; $i++) {
                    dispatch(new Orgaos($this->url, $i));
                }
            }
        } catch (\Exception $e) {
            $this->job->fail($e);
            echo $e->getMessage() . PHP_EOL;
        }
    }

    protected function updateOrCreateOrgao($orgao)
    {
        try {
            $orgaoModel = Orgao::firstOrCreate(
                ['ext_id' => $orgao['id']],
            );

            if ($orgaoModel->wasRecentlyCreated || $orgaoModel->next_run < Carbon::now()->toDateString()) {
                if (!empty($orgao['uuidLogo'])) {
                    $this->downloadFile(
                        "https://s3-sa-east-1.amazonaws.com/figuras.tecconcursos.com.br/" . $orgao['uuidLogo'],
                        "orgaos"
                    );
                }

                $orgaoModel->nome = $orgao['nome'];
                $orgaoModel->sigla = $orgao['sigla'];
                $orgaoModel->url = $orgao['url'];
                $orgaoModel->uuid_logo = $orgao['uuidLogo'];
                $orgaoModel->next_run = Carbon::now()->addDays(5);
                $orgaoModel->save();
                echo "Orgão - {$orgao['nome']} Atualizado com Sucesso!" . PHP_EOL;
            }
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
                $fileType = $response->getHeader('Content-Type');

                $extension = '';
                if ($fileType[0] == "application/pdf") {
                    $extension = ".pdf";
                } elseif ($fileType[0] == "image/jpeg" || $fileType[0] == "image/pjpeg" || $fileType[0] == "image/jpeg" || $fileType[0] == "image/pjpeg") {
                    $extension = ".jpg";
                } elseif ($fileType[0] == "image/png") {
                    $extension = ".png";
                } elseif ($fileType == "image/gif") {
                    $extension = ".gif";
                } elseif ($fileType[0] == "application/zip") {
                    $extension = ".zip";
                } elseif ($fileType[0] == "application/x-rar-compressed") {
                    $extension = ".rar";
                } elseif ($fileType[0] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document") {
                    $extension = ".docx";
                } elseif ($fileType[0] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
                    $extension = ".xlsx";
                } elseif ($fileType[0] == "application/vnd.openxmlformats-officedocument.presentationml.presentation") {
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
