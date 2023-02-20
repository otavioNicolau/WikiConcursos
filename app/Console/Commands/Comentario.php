<?php

namespace App\Console\Commands;

use App\Jobs\Comentarios;
use App\Models\Questao;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class Comentario extends Command
{
    /**
     * Execute the console command.
     *
     * @return int
     */

    use DispatchesJobs;
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


    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $headers = [
            'authority' => 'www.tecconcursos.com.br',
            'method' => 'POST',
            'path' => '/api/questoes/199341/resolucao',
            'scheme' => 'https',
            'accept' => 'application/json, text/plain, * / *',
            'accept-encoding' => 'gzip, deflate, br',
            'accept-language' => 'pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7',
            'cache-control' => 'no-cache',
            'content-type' => 'application/x-www-form-urlencoded; charset=UTF-8',
            'cookie' => '_ga=GA1.3.1154984221.1673711946; _gid=GA1.3.92734287.1673711946; _fbp=fb.2.1673711946824.1020756054; TecPermanecerLogado=ODU3MzQzLG90YXZpby5uaWNvbGxhdUBnbWFpbC5jb20sJDJhJDEyJFcuTTVyY1BRRmR0bzM2VnpGYlY2d09YZmROMkJSZ08uL3prbG0yeEZSOWZFRm5YS2RSTDVt; busca-por-enunciado-usuario-857343={%22busca%22:%22#12457%22%2C%22questao%22:12457}; JSESSIONID=47D43577CC5FB152E668B0E87A369A8C; AWSALB=wVVUEqD8ri3rZByA4FGiSONZ0UEkmP2E6euDdXZ0/r4PZQW92d/KlkLd9mmKxWLz218vugrKkdtHLMQE4GWwB96uAalC1/2j7QKyZFoX/M5iFJsnkvNHOC3qRhF6; AWSALBCORS=wVVUEqD8ri3rZByA4FGiSONZ0UEkmP2E6euDdXZ0/r4PZQW92d/KlkLd9mmKxWLz218vugrKkdtHLMQE4GWwB96uAalC1/2j7QKyZFoX/M5iFJsnkvNHOC3qRhF6; _gali=alternativa-0',
            'if-modified-since' => 'Mon, 26 Jul 1997 05:00:00 GMT',
            'logado' => 'true',
            'origin' => 'https://www.tecconcursos.com.br',
            'pragma' => 'no-cache',
            'referer' => 'https://www.tecconcursos.com.br/questoes/199341',
            'sec-ch-ua' => '"Not_A Brand";v="99", "Google Chrome";v="109", "Chromium";v="109"',
            'sec-ch-ua-mobile' => '?0',
            'sec-ch-ua-platform' => '"Windows"',
            'sec-fetch-dest' => 'empty',
            'sec-fetch-mode' => 'cors',
            'sec-fetch-site' => 'same-origin',
            'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36'
        ];

        $questoes = Questao::where('next_comentario_run', '<', Carbon::now())->get();

        foreach ($questoes as $questao) {
            $this->dispatch(
                new Comentarios(
                    "https://www.tecconcursos.com.br/api/questoes/{$questao->ext_id}/comentario",
                    $questao->ext_id,
                    $headers
                )
            );
            $questao->next_comentario_run = Carbon::now()->addDays(5);
            $questao->save();
        }
    }
}
