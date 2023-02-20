<?php

namespace App\Console\Commands;

use App\Jobs\CargosGPT;
use App\Models\Cargo;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Console\Command;

class CargoGPT extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:cargo-gpt';

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

        $cargos = Cargo::where('gpt_worked', false)->get();

        foreach ($cargos as $cargo) {
            $this->dispatch(
                new CargosGPT(
                    $cargo->id
                )
            );
        }
    }
}
