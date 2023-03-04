<?php

namespace App\Jobs;

use App\Models\Profissao;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use romanzipp\QueueMonitor\Traits\IsMonitored;

class Profissoes implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use IsMonitored;

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
            $response = $client->get(
                $this->url,
                [
                    'headers' => getDefaultHeaders(),
                    'cache' => false
                ]);

            $statusCode = $response->getStatusCode();

            if ($statusCode == 200) {
                $data = json_decode($response->getBody(), true);
                $profissoes = $data['profissoes'];

                foreach ($profissoes as $profissao) {
                    $this->updateOrCreateProfissao($profissao);
                }
            }
            sleep(getDelay());
        } catch (\Exception $e) {
            $this->job->fail($e);
            echo $e->getMessage() . PHP_EOL;
        }
    }

    protected function updateOrCreateProfissao($profissao)
    {
        try {
            $profissaoModel = Profissao::firstOrCreate(
                ['ext_id' => $profissao['id']],
            );

            if ($profissaoModel->wasRecentlyCreated || $profissaoModel->next_run < Carbon::now()->toDateString()) {
                $profissaoModel->nome = $profissao['nome'];
                $profissaoModel->next_run = Carbon::now()->addDays(5);
                $profissaoModel->save();
                echo "Profissão - {$profissao['nome']} Atualizada com Sucesso!" . PHP_EOL;
            }
        } catch (\Exception $e) {
            $this->job->fail($e);
            echo $e->getMessage() . PHP_EOL;
        }
    }
}
