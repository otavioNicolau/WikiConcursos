<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class BotGpt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:start-gpt';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Iniciar a Execução de todos os JOBS que Realizam função no GPT';

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
        $this->info('Iniciando a solicitação do modulo GPT...');

        $commands = [
            'bot:area-gpt',
            'bot:assunto-gpt',
            'bot:banca-gpt',
            'bot:cargo-gpt',
            'bot:comentario-gpt',
            'bot:escolaridade-gpt',
            'bot:materia-gpt',
            'bot:orgao-gpt',
            'bot:profissao-pgt'
        ];

        foreach ($commands as $command) {
            $this->call($command);
        }

        $this->info('Finalizando a solicitação do modulo GPT...');

    }
}
