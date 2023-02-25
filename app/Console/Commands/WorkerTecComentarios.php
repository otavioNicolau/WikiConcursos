<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class WorkerTecComentarios extends Command
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
    protected $signature = 'worker:comentario';

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
        $this->info('Iniciando o WORKER COMENTARIO.');
        $this->call('queue:work', [
            '--queue' => 'comentarios'
        ]);
    }
}
