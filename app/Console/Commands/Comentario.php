<?php

namespace App\Console\Commands;

use App\Jobs\Comentarios;
use App\Models\Questao;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class Comentario extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:comentario';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Realizar a coleta das informações referente aos comentarios das questoes no tec.';

    /**
     * Execute the console command.
     *
     * @return int
     */

    use DispatchesJobs;


    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        $headers = [
            'authority' => 'www.tecconcursos.com.br',
            'method' => 'POST',
            'path' => '/api/questoes/1632188/resolucao',
            'scheme' => 'https',
            'accept' => 'application/json, text/plain, * / *',
            'accept-encoding' => 'gzip, deflate, br',
            'accept-language' => 'pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7',
            'cache-control' => 'no-cache',
            'content-type' => 'application/x-www-form-urlencoded; charset=UTF-8',
            'cookie' => '_ga=GA1.3.1235923260.1660774669; _fbp=fb.2.1672096463829.1678068607; _gid=GA1.3.1241576195.1673563183; JSESSIONID=0CE41BD2C56195E580FDE3B6C690BD9D; TecPermanecerLogado=Mjc2NzU2MixkYXkubmljb2xsYXVAZ21haWwuY29tLCQyYSQxMiRVRlFzY3E4TUszUndJQkxMRS9tUDJPdUVvazFxTkdhanEzY1RXT1ZxVWpOS0p6cXd4S0xwLg==; _gat=1; _gat_UA-32462178-1=1; AWSALB=GiMcjokjfxLZ+lGd9P7opaZvTjna/5KclDOclIhp7gS5h8SeQZLyNIf7K6EcIhNyMN/yozJm5yUcnFma1z/2Nh+51KviJB7pmOmk/awsxHrrv/cb53rTnOYB1BRo; AWSALBCORS=GiMcjokjfxLZ+lGd9P7opaZvTjna/5KclDOclIhp7gS5h8SeQZLyNIf7K6EcIhNyMN/yozJm5yUcnFma1z/2Nh+51KviJB7pmOmk/awsxHrrv/cb53rTnOYB1BRo; _gali=alternativa-1',
            'if-modified-since' => 'Mon, 26 Jul 1997 05:00:00 GMT',
            'logado' => 'true',
            'origin' => 'https://www.tecconcursos.com.br',
            'pragma' => 'no-cache',
            'referer' => 'https://www.tecconcursos.com.br/concursos/perito-criminal-pc-mg-2005/questoesObjetivas/!provas',
            'sec-ch-ua' => '"Not_A Brand";v="99", "Google Chrome";v="109", "Chromium";v="109"',
            'sec-ch-ua-mobile' => '?0',
            'sec-ch-ua-platform' => '"Windows"',
            'sec-fetch-dest' => 'empty',
            'sec-fetch-mode' => 'cors',
            'sec-fetch-site' => 'same-origin',
            'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36'
        ];

        $questoes = Questao::all();

        foreach ($questoes as $questao) {
            $this->dispatch(
                new Comentarios(
                    "https://www.tecconcursos.com.br/api/questoes/" . $questao->ext_id . "/comentario",
                    $questao->ext_id,
                    $headers
                )
            );
        }
    }
}
