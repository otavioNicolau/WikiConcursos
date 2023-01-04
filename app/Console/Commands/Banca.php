<?php

namespace App\Console\Commands;

use App\Jobs\Bancas;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class Banca extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:banca';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Realizar a coleta das informações referente as bancas no tec.';

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
        $this->dispatch(new Bancas);
    }
}
