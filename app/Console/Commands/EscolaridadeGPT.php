<?php

namespace App\Console\Commands;

use App\Jobs\EscolaridadesGPT;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Console\Command;

use App\Models\Escolaridade;
use Carbon\Carbon;

class EscolaridadeGPT extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:escolaridade-gpt';

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
        $escolaridades = Escolaridade::where('next_run', '<', Carbon::now())->get();

        foreach ($escolaridades as $escolaridade) {
            $this->dispatch(
                new EscolaridadesGPT(
                    $escolaridade->id
                )
            );
        }
    }
}
