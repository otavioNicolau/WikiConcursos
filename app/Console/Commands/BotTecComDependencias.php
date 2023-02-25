<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class BotTecComDependencias extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:com-dependencias';

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
        $this->info('Iniciando os modulos de coleta de dados no tec com dependencia...');

        $commands = [
            'bot:assunto', 
            'bot:cargo', 
        ];

        foreach ($commands as $command) {
            $this->info("INICIANDO O MODULO {$command}");
            $this->call($command);
        }

    }
}
