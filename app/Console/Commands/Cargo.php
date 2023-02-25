<?php

namespace App\Console\Commands;

use App\Jobs\Cargos;
use App\Models\Orgao;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class Cargo extends Command
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
    protected $signature = 'bot:cargo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Realizar a coleta das informações referente as cargo no tec.';


    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $orgaos = Orgao::where(function ($query) {
            $query->whereDate('next_cargo_run', '<', Carbon::now()->toDateString())
                  ->orWhereNull('next_cargo_run');
        })->get();

        foreach ($orgaos as $orgao) {
            
            $job =  new Cargos(
                "https://www.tecconcursos.com.br/api/cargos",
                $orgao->ext_id
            );
            $job->onQueue('cargos');
            $this->dispatch($job);

            $orgao->next_cargo_run = Carbon::now()->addDays(5);
            $orgao->save();
        }
    }
}
