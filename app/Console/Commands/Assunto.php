<?php

namespace App\Console\Commands;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Console\Command;

use App\Jobs\Assuntos;
use App\Models\Materia;

class Assunto extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:assunto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Realizar a coleta das informações referente aos assuntos no tec.';

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

        $materias = Materia::all();

        foreach ($materias as $materia) {
            $this->dispatch(
                new Assuntos(
                    "https://www.tecconcursos.com.br/api/assuntos",
                    $materia->id
                )
            );
        }
    }
}
