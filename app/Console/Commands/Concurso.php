<?php

namespace App\Console\Commands;

use App\Jobs\Concursos;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class Concurso extends Command
{

        /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:concurso';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Realizar a coleta das informações referente aos concursos no tec.';

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
        $this->dispatch(new Concursos);
    }
}
