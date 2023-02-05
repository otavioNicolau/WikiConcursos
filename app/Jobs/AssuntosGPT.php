<?php

namespace App\Jobs;

use App\Models\Assunto;
use App\Models\Materia;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Queue\SerializesModels;
use OpenAI\Laravel\Facades\OpenAI;
use romanzipp\QueueMonitor\Traits\IsMonitored;

class AssuntosGPT implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use IsMonitored;

    protected $assunto;
    public $tries = 0;


    public function __construct($assunto)
    {
        $this->assunto = $assunto;
    }

    public function middleware()
    {
        return [
            new RateLimited('assuntosGPT')
        ];
    }
    public function handle()
    {
        try {

            $assuntoModel = Assunto::where('id', $this->assunto)->first();
            $materiaModel = Materia::where('id', $assuntoModel->materia_id)->first();

            $GPT = OpenAI::completions()->create([
                'model' => 'text-davinci-003',
                'max_tokens' => 2060,
                "temperature" => 0.1,
                'prompt' => "
                
                ATUE COMO PROFESSOR E CRIE UM ARTIGO COMPLETO COM LINGUAGEM DE FACIL ENTENDIMENTO SOBRE O Assunto ABAIXO:
                O TEXTO TEM QUE ESTAR NA sintaxe de marcação do MediaWik USANDO (=,==,===, * [[]] e etc..).
                O TEXTO TEM QUE ESTAR EM TOPICOS DE FORMA ORGANIZADA.
                O TEXTO TEM QUE TER NO MAXIMO 300 PALAVRAS NO ARTIGO. 
                Matéria: {$materiaModel->nome}
                Assunto: {$assuntoModel->nome}
                ",
            ]);

            $assuntoModel->descricao = $GPT['choices'][0]['text']; 
            $assuntoModel->save();
            echo "ASSUNTO - {$assuntoModel->nome} gerado com sucesso!" . PHP_EOL;
            

        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }
}
