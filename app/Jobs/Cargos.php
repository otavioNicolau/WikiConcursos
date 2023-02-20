<?php

namespace App\Jobs;

use App\Models\Cargo;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Queue\SerializesModels;
use romanzipp\QueueMonitor\Traits\IsMonitored;

class Cargos implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, IsMonitored;

    protected $url;
    protected $orgao_id;
    public $tries = 0;


    public function __construct($url, $orgao_id)
    {
        $this->url = $url;
        $this->orgao_id = $orgao_id;
    }


    public function handle()
    {
        try {
            $client = new Client();
            $response = $client->get($this->url, [
                'query' => [
                    'formato' => 'OBJETIVA',
                    'hierarquico' => true,
                    'orgao' => $this->orgao_id,
                    'universo' => ''
                ]
            ]);

            $statusCode = $response->getStatusCode();

            if ($statusCode == 200) {

                $data = json_decode($response->getBody(), true);
                $cargos = $data['cargos'];

                foreach ($cargos as $cargo) {
                    $this->updateOrCreateCargo($cargo);

                    if ($cargo['subTree']) {
                        foreach ($cargo['subTree'] as $areaCargo) {
                            $this->updateOrCreateCargo($areaCargo, $cargo['id']);
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
            new RateLimited('cargos')
        ];
    }

    protected function updateOrCreatecargo($cargo, $descendentes_de = 0)
    {
        $cargoModel = Cargo::where('ext_id', $cargo['id'])->first();

        if ($cargoModel) {
            $cargoModel->nome = $cargo['nome'];
            $cargoModel->orgao_id = $this->orgao_id;
            $cargoModel->tipo = $cargo['tipo'];
            $cargoModel->title = array_key_exists('title', $cargo) ? $cargo['title'] : " * ";
            $cargoModel->descendentes_de = $descendentes_de;
            $cargoModel->next_run  = Carbon::now()->addDays(5);
            $cargoModel->save();
            echo "Cargo - {$cargo['nome']} Atualizada com Sucesso!" . PHP_EOL;
        } else {
            Cargo::create([
                'ext_id' => $cargo['id'],
                'nome' => $cargo['nome'],
                'orgao_id' => $this->orgao_id,
                'tipo' => $cargo['tipo'],
                'title' => array_key_exists('title', $cargo) ? $cargo['title'] : " * ",
                'descendentes_de' => $descendentes_de,
                'next_run' => Carbon::now()->addDays(5)
            ]);
            echo "Cargo - {$cargo['nome']} Foi Criada com sucesso" . PHP_EOL;
        }
    }
}
