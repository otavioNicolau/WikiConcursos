<?php

namespace App\Console\Commands;

use App\Jobs\materiasGPT;
use App\Models\Materia;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Console\Command;

class MateriaGPT extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:materia-gpt';

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

        $materias = Materia::where('gpt_worked', false)->get();

        foreach ($materias as $materia) {
            $this->dispatch(
                new materiasGPT(
                    $materia->id
                )
            );
        }
    }
}
