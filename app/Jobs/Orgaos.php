<?php

namespace App\Jobs;

use App\Models\Orgao;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Orgaos implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $url;
    protected $num;

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
            echo $e->getMessage() . PHP_EOL;
        }
    }

    protected function updateOrCreateOrgao($orgao)
    {
        $orgaoModel = Orgao::where('ext_id', $orgao['id'])->first();

        if ($orgaoModel) {
            $orgaoModel->nome = $orgao['nome'];
            $orgaoModel->sigla = $orgao['sigla'];
            $orgaoModel->url = $orgao['url'];
            $orgaoModel->uuid_logo = $orgao['uuidLogo'];
            $orgaoModel->orgao_regiao = $orgao['orgao_regiao'];
            $orgaoModel->orgao_uuid = $orgao['orgao_uuid'];
            // $orgaoModel->caminho_logotipo_orgao = $orgao['caminho_logotipo_orgao'];
            $orgaoModel->save();
            echo "Orgão - {$orgao['nome']} Atualizado com Sucesso!" . PHP_EOL;
        } else {
            Orgao::create([
                'ext_id' => $orgao['id'],
                'nome' => $orgao['nome'],
                'sigla' => $orgao['sigla'],
                'url' => $orgao['url'],
                'uuid_logo' => $orgao['uuidLogo'],
                //'orgao_regiao' => $orgao['orgao_regiao'],
                //'orgao_uuid' => $orgao['orgao_uuid'],
                //'caminho_logotipo_orgao' => $orgao['caminho_logotipo_orgao'],
            ]);
            echo "Orgão - {$orgao['nome']} Foi Criada com sucesso" . PHP_EOL;
        }
    }
}
