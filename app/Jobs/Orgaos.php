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
                    $job = new Orgaos($this->url, $i);
                    $job->onQueue('orgaos');
                    dispatch($job);
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
                    $job = new Downloads(
                        "https://s3-sa-east-1.amazonaws.com/figuras.tecconcursos.com.br/" . $orgao['uuidLogo'],
                        "orgaos"
                    );
                    $job->onQueue('downloads');
                    dispatch($job);
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
