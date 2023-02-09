<?php

namespace App\Console\Commands;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Console\Command;

use App\Jobs\AssuntosGPT;
use App\Models\Assunto;

class AssuntoGPT extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:assunto-gpt';

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

        $areas = Area::where('gpt_worked', true)->get();


        $assuntos = Assunto::all();

        foreach ($assuntos as $assunto) {
            $this->dispatch(
                new AssuntosGPT(
                    $assunto->id
                )
            );
        }
    }
}
