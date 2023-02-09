<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class BotTec extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:start-tec';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Iniciar a Execução de todos os JOBS';

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
        $this->info('Iniciando a coleta de dados...');

        $commands = [
            'bot:area',
            'bot:assunto',
            'bot:banca',
            'bot:cargo',
            'bot:comentario',
            'bot:edital',
            'bot:escolaridade',
            'bot:materia',
            'bot:orgao',
            'bot:profissao',
            'bot:questao',
        ];

        foreach ($commands as $command) {
            $this->call($command);
        }

        $this->info('Finalizando a coleta de dados...');


    }
}
