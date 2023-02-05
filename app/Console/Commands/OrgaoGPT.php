<?php

namespace App\Console\Commands;

use App\Jobs\orgaosGPT;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Console\Command;

use App\Models\Escolaridade;
use App\Models\Orgao;

class OrgaoGPT extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:orgao-gpt';

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

        $orgaos = Orgao::all();

        foreach ($orgaos as $orgao) {
            $this->dispatch(
                new OrgaosGPT(
                    $orgao->id
                )
            );
        }
    }
}
