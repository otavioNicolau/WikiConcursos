<?php

namespace App\Console\Commands;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Console\Command;

use App\Jobs\Assuntos;
use App\Models\Materia;
use Carbon\Carbon;

class Assunto extends Command
{
    /**
     * Execute the console command.
     *
     * @return int
     */

    use DispatchesJobs;
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


    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        $materias = Materia::where(function ($query) {
            $query->whereDate('next_assuntos_run', '<', Carbon::now()->toDateString())
                  ->orWhereNull('next_assuntos_run');
        })->limit(30000)->get();

        foreach ($materias as $materia) {
            echo "JOB ASSUNTO - Inserido com Sucesso!" . PHP_EOL;

            $job = new Assuntos(
                "https://www.tecconcursos.com.br/api/assuntos",
                $materia->ext_id
            );
            $job->onQueue('assuntos');
            $this->dispatch($job);

            $materia->next_assuntos_run = Carbon::now()->addDays(5);
            $materia->save();
        }
    }
}
