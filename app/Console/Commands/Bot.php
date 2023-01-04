<?php

namespace App\Console\Commands;

use App\Jobs\Areas;
use App\Jobs\Assuntos;
use App\Jobs\Bancas;
use App\Jobs\Cargos;
use App\Jobs\Comentarios;
use App\Jobs\Concursos;
use App\Jobs\Editais;
use App\Jobs\Escolaridades;
use App\Jobs\Materias;
use App\Jobs\Orgaos;
use App\Jobs\Profissoes;
use App\Jobs\Questoes;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class bot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:start';

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
        $this->dispatch(new Escolaridades);
        $this->dispatch(new Profissoes);
        $this->dispatch(new Areas);
        $this->dispatch(new Orgaos);
        $this->dispatch(new Materias);
        $this->dispatch(new Assuntos);
        $this->dispatch(new Bancas);
        $this->dispatch(new Cargos);
        $this->dispatch(new Comentarios);
        $this->dispatch(new Concursos);
        $this->dispatch(new Editais);
        $this->dispatch(new Questoes);

        $this->info('Finalizando a coleta de dados...');


    }
}
