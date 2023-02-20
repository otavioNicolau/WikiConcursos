<?php

namespace App\Console\Commands;

use App\Jobs\AreasGPT;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Console\Command;
use App\Models\Area;

class AreaGPT extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:area-gpt';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'gerar informações com o gpt.';

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

        $areas = Area::where('gpt_worked', false)->get();

        foreach ($areas as $area) {
            $this->dispatch(
                new AreasGPT(
                    $area->id
                )
            );
        }
    }
}
