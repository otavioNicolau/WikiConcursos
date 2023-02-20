<?php

namespace App\Console\Commands;

use App\Jobs\Cargos;
use App\Models\Orgao;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class CargoComand extends Command
{
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
        $orgaos = Orgao::where('next_cargo_run', '<', Carbon::now())->get();

        foreach ($orgaos as $orgao) {
            $this->dispatch(
                new Cargos(
                    "https://www.tecconcursos.com.br/api/cargos",
                    $orgao->ext_id
                )
            );
            $orgao->next_cargo_run = Carbon::now()->addDays(5);
            $orgao->save();
        }
    }
}
