<?php

namespace App\Jobs;

use App\Models\Comentario;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Queue\SerializesModels;
use OpenAI\Laravel\Facades\OpenAI;
use romanzipp\QueueMonitor\Traits\IsMonitored;

class ComentariosGPT implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use IsMonitored;

    protected $comentario;
    public $tries = 0;


    public function __construct($comentario)
    {
        $this->comentario = $comentario;
    }

    public function middleware()
    {
        return [
            new RateLimited('comentariosGPT')
        ];
    }
    public function handle()
    {
        try {

            $comentarioModel = Comentario::where('id', $this->comentario)->first();

            $GPT = OpenAI::completions()->create([
                'model' => 'text-davinci-003',
                'max_tokens' => 2060,
                "temperature" => 0.5,
                'prompt' => "
                
                REVISE E Reformule o comentario: {$comentarioModel->comentario}
                O TEXTO TEM QUE ESTAR NA sintaxe de marcação do MediaWik USANDO (=,==,===, * [[]] e etc..).
                O TEXTO TEM QUE TER NO MAXIMO 300 PALAVRAS NO ARTIGO.
                ",
            ]);

            $comentarioModel->comentario_GPT = $GPT['choices'][0]['text']; 
            $comentarioModel->save();
            echo "comentario - {$comentarioModel->id_questao } gerado com sucesso!" . PHP_EOL;
            

        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }
}
