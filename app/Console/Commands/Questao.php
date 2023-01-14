<?php

namespace App\Console\Commands;

use App\Jobs\Questoes;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class Questao extends Command
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
    protected $signature = 'bot:questao';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Realizar a coleta das informações referente as questões no tec.';


    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $data = [
            'alternativa' => '5',
            '_method' => 'put'
        ];

        
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
            'cookie' => '_ga=GA1.3.1235923260.1660774669; _fbp=fb.2.1672096463829.1678068607; _gid=GA1.3.37202766.1673697246; JSESSIONID=BD93513721D957B91C3F61FF3095962B; _gat=1; _gat_UA-32462178-1=1; TecPermanecerLogado=ODU3MzQzLG90YXZpby5uaWNvbGxhdUBnbWFpbC5jb20sJDJhJDEyJFcuTTVyY1BRRmR0bzM2VnpGYlY2d09YZmROMkJSZ08uL3prbG0yeEZSOWZFRm5YS2RSTDVt; AWSALB=iOAIRJu6YIkrzA0tEMEHHqjNx623gfwHqYvuQny2kvmX/vFz4C1BlRRQtJIu7dhenSniSYjI45i0uWHsigqi+sPO2IAw/WLGg0lJLRIF/xx7ipAjoSR8dxgKX/ha; AWSALBCORS=iOAIRJu6YIkrzA0tEMEHHqjNx623gfwHqYvuQny2kvmX/vFz4C1BlRRQtJIu7dhenSniSYjI45i0uWHsigqi+sPO2IAw/WLGg0lJLRIF/xx7ipAjoSR8dxgKX/ha; _gali=alternativa-4',
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


        $this->dispatch(
            new Questoes(
                "https://www.tecconcursos.com.br/api/questoes/155802/resolucao",
                $data,
                $headers
            )
        );
    }
}
