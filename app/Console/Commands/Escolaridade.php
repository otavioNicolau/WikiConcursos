<?php

namespace App\Console\Commands;

use App\Jobs\Escolaridades;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class Escolaridade extends Command
{
     /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:escolaridade';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Realizar a coleta das informações referente aos niveis de escolaridade no tec.';

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
        $job = new Escolaridades("https://www.tecconcursos.com.br/api/enums/escolaridades");
        $job->onQueue('escolaridades');
        $this->dispatch($job);
    }
}
