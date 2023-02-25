<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class WorkerTecSemDependencias extends Command
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
    protected $signature = 'worker:sem-dependencias';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Iniciar a Execução de todos os JOBS';


    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Iniciando o WORKER dos modulos que não precisam de dependencias.');
        $this->info("profissoes,areas,bancas,escolaridades,materias,orgaos");
        $this->call('queue:work', [
            '--queue' => 'profissoes,areas,bancas,escolaridades,materias,orgaos'
        ]);
    }
}
