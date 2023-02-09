<?php

namespace App\Jobs;

use App\Models\profissao;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Queue\SerializesModels;
use OpenAI\Laravel\Facades\OpenAI;
use romanzipp\QueueMonitor\Traits\IsMonitored;

class ProfissoesGPT implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use IsMonitored;

    protected $profissao;
    public $tries = 0;


    public function __construct($profissao)
    {
        $this->profissao = $profissao;
    }

    public function middleware()
    {
        return [
            new RateLimited('profissoesGPT')
        ];
    }
    public function handle()
    {
        try {

            $profissaoModel = profissao::where('id', $this->profissao)->first();

            $GPT = OpenAI::completions()->create([
                'model' => 'text-davinci-003',
                'max_tokens' => 2060,
                "temperature" => 0.1,
                'prompt' => "
                
                Escreva um artigo sobre a profissao: {$profissaoModel->nome}:
                O TEXTO TEM QUE ESTAR NA sintaxe de marcação do MediaWik USANDO (=,==,===, * [[]] e etc..).
                O TEXTO TEM QUE ESTAR EM TOPICOS DE FORMA ORGANIZADA.
                O TEXTO TEM QUE TER NO MAXIMO 300 PALAVRAS NO ARTIGO.    
                profissao: {$profissaoModel->nome}
                ",
            ]);

            $profissaoModel->descricao = $GPT['choices'][0]['text']; 
            $profissaoModel->save();
            echo "profissao - {$profissaoModel->nome} gerado com sucesso!" . PHP_EOL;
            

        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }
}
