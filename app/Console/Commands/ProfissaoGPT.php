<?php

namespace App\Console\Commands;

use App\Jobs\ProfissoesGPT;
use App\Models\Profissao;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Console\Command;

class ProfissaoGPT extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:profissao-gpt';

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

        $profissoes = Profissao::where('gpt_worked', true)->get();

        foreach ($profissoes as $profissao) {
            $this->dispatch(
                new ProfissoesGPT(
                    $profissao->id
                )
            );
        }
    }
}
