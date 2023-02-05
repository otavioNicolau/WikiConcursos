<?php

namespace App\Jobs;

use App\Models\escolaridade;
use App\Models\Materia;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Queue\SerializesModels;
use OpenAI\Laravel\Facades\OpenAI;
use romanzipp\QueueMonitor\Traits\IsMonitored;

class EscolaridadesGPT implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use IsMonitored;

    protected $escolaridade;
    public $tries = 0;


    public function __construct($escolaridade)
    {
        $this->escolaridade = $escolaridade;
    }

    public function middleware()
    {
        return [
            new RateLimited('escolaridadesGPT')
        ];
    }
    public function handle()
    {
        try {

            $escolaridadeModel = Escolaridade::where('id', $this->escolaridade)->first();

            $GPT = OpenAI::completions()->create([
                'model' => 'text-davinci-003',
                'max_tokens' => 2060,
                "temperature" => 0.1,
                'prompt' => "
                
                ATUE COMO PROFESSOR E CRIE UM ARTIGO COMPLETO COM LINGUAGEM DE FACIL ENTENDIMENTO SOBRE a Escolaridade ABAIXO:
                O TEXTO TEM QUE ESTAR NA sintaxe de marcação do MediaWik USANDO (=,==,===, * [[]] e etc..).
                O TEXTO TEM QUE ESTAR EM TOPICOS DE FORMA ORGANIZADA.
                O TEXTO TEM QUE TER NO MAXIMO 300 PALAVRAS NO ARTIGO.      
                Escolaridade: {$escolaridadeModel->nome}
                ",
            ]);

            $escolaridadeModel->descricao = $GPT['choices'][0]['text']; 
            $escolaridadeModel->save();
            echo "Escolaridade - {$escolaridadeModel->nome} gerado com sucesso!" . PHP_EOL;
            

        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }
}
