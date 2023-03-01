<?php

namespace App\Console\Commands;

use App\Jobs\Questoes;
use App\Models\Questao as QuestaoModel;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class Questao extends Command
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
    protected $signature = 'bot:questao';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Realizar a coleta das informações referente as questões no tec.';


    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $data = [
            'alternativa' => '1',
            '_method' => 'put'
        ];

        $questoes = QuestaoModel::where(function ($query) {
            $query->whereDate('next_run', '<', Carbon::now()->toDateString())
                  ->orWhereNull('next_run');
        })->get();

        foreach ($questoes as $questao) {
            echo "JOB QUESTÃO - Inserido com Sucesso!" . PHP_EOL;

            $job = new Questoes(
                "https://www.tecconcursos.com.br/api/questoes/{$questao->ext_id}",
                //"https://www.tecconcursos.com.br/api/questoes/{$questao->ext_id}/resolucao",
                $data,
                getDefaultHeaders()
            );
            $job->onQueue('questoes');
            $this->dispatch($job);
        }
    }
}
