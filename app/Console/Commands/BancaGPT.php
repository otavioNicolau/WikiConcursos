<?php

namespace App\Console\Commands;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Console\Command;

use App\Jobs\BancasGPT;
use App\Models\Banca;

class BancaGPT extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:banca-gpt';

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

        $bancas = Banca::all();

        foreach ($bancas as $banca) {
            $this->dispatch(
                new BancasGPT(
                    $banca->id
                )
            );
        }
    }
}
