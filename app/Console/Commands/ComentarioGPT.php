<?php

namespace App\Console\Commands;

use App\Jobs\ComentariosGPT;
use App\Models\Comentario;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Console\Command;

class ComentarioGPT extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:comentario-gpt';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'gerar informações com o gpt.';

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

        $cargos = Comentario::where('gpt_worked', true)->get();

        foreach ($comentarios as $comentario) {
            $this->dispatch(
                new ComentariosGPT(
                    $comentario->id
                )
            );
        }
    }
}
