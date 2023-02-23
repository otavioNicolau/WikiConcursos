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
            'bot:profissao', // SEM DEPENDENCIA
            'bot:area', // SEM DEPENDENCIA
            'bot:banca', // SEM DEPENDENCIA
            'bot:edital', // SEM DEPENDENCIA 
            'bot:escolaridade', // SEM DEPENDENCIA
            'bot:materia', // SEM DEPENDENCIA
            'bot:orgao', // SEM DEPENDENCIA
            'bot:prova', //  CONCURSO
            'bot:questao', //  PROVAS
            'bot:assunto', // MATERIA
            'bot:cargo', // ORGAO
            'bot:comentario', // QUESTAO
        ];

        foreach ($commands as $command) {
            $this->info("INICIANDO O {$command}");
            $this->call($command);
        }

        $this->info('Finalizando a coleta de dados...');


    }
}
