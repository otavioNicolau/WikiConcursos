<?php

namespace App\Console\Commands;

use App\Jobs\Provas;
use App\Models\Concurso;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class Prova extends Command
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
    protected $signature = 'bot:prova';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Realizar a coleta das informações referente as provas das questoes no tec.';


    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $concuros = Concurso::where(function ($query) {
            $query->whereDate('next_provas_run', '<', Carbon::now()->toDateString())
                  ->orWhereNull('next_provas_run');
        })->get();

        foreach ($concuros as $concuro) {
            echo "JOB Provas - Inserido com Sucesso!" . PHP_EOL;

            $job = new Provas(
                "https://www.tecconcursos.com.br/api/concursos/questoes/{$concuro->ext_id}/provas",
                $concuro->ext_id
            );
            $job->onQueue('provas');
            $this->dispatch($job);

            $concuro->next_provas_run = Carbon::now()->addDays(5);
            $concuro->save();
        }
    }
}
