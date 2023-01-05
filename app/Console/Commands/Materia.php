<?php

namespace App\Console\Commands;

use App\Jobs\Materias;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class Materia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:materia';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Realizar a coleta das informações referente as materias no tec.';

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

        $this->dispatch(
            new Materias(
                "https://www.tecconcursos.com.br/api/materias/busca-rapida",
                1
            )
        );
    }
}
