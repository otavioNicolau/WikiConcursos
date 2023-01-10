<?php

namespace App\Console\Commands;

use App\Jobs\Areas;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class Area extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:area';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Realizar a coleta das informações referente as área no tec.';

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
        $this->dispatch(new Areas("https://www.tecconcursos.com.br/api/enums/areas"));
    }
}
