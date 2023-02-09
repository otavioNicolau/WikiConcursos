<?php

namespace App\Jobs;

use App\Models\Materia;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Queue\SerializesModels;
use OpenAI\Laravel\Facades\OpenAI;
use romanzipp\QueueMonitor\Traits\IsMonitored;

class MateriasGPT implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use IsMonitored;

    protected $materia;
    public $tries = 0;


    public function __construct($materia)
    {
        $this->materia = $materia;
    }

    public function middleware()
    {
        return [
            new RateLimited('materiasGPT')
        ];
    }
    public function handle()
    {
        try {

            $materiaModel = Materia::where('id', $this->materia)->first();

            $GPT = OpenAI::completions()->create([
                'model' => 'text-davinci-003',
                'max_tokens' => 2060,
                "temperature" => 0.1,
                'prompt' => "
                
                Escreva um artigo sobre a materia: {$materiaModel->nome}:
                O TEXTO TEM QUE ESTAR NA sintaxe de marcação do MediaWik USANDO (=,==,===, * [[]] e etc..).
                O TEXTO TEM QUE ESTAR EM TOPICOS DE FORMA ORGANIZADA.
                O TEXTO TEM QUE TER NO MAXIMO 300 PALAVRAS NO ARTIGO.    
                materia: {$materiaModel->nome}
                ",
            ]);

            $materiaModel->descricao = $GPT['choices'][0]['text']; 
            $materiaModel->save();
            echo "materia - {$materiaModel->nome} gerado com sucesso!" . PHP_EOL;
            

        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }
}
