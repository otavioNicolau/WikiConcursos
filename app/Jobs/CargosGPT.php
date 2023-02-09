<?php

namespace App\Jobs;

use App\Models\cargo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Queue\SerializesModels;
use OpenAI\Laravel\Facades\OpenAI;
use romanzipp\QueueMonitor\Traits\IsMonitored;

class CargosGPT implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use IsMonitored;

    protected $cargo;
    public $tries = 0;


    public function __construct($cargo)
    {
        $this->cargo = $cargo;
    }

    public function middleware()
    {
        return [
            new RateLimited('cargosGPT')
        ];
    }
    public function handle()
    {
        try {

            $cargoModel = cargo::where('id', $this->cargo)->first();

            $GPT = OpenAI::completions()->create([
                'model' => 'text-davinci-003',
                'max_tokens' => 2060,
                "temperature" => 0.1,
                'prompt' => "
                
                ATUE COMO PROFESSOR E CRIE UM ARTIGO COMPLETO COM LINGUAGEM DE FACIL ENTENDIMENTO SOBRE o cargo ABAIXO:
                O TEXTO TEM QUE ESTAR NA sintaxe de marcação do MediaWik USANDO (=,==,===, * [[]] e etc..).
                O TEXTO TEM QUE ESTAR EM TOPICOS DE FORMA ORGANIZADA.
                O TEXTO TEM QUE TER NO MAXIMO 300 PALAVRAS NO ARTIGO.    
                cargo: {$cargoModel->nome}
                ",
            ]);

            $cargoModel->descricao = $GPT['choices'][0]['text']; 
            $cargoModel->save();
            echo "cargo - {$cargoModel->nome} gerado com sucesso!" . PHP_EOL;
            

        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }
}
