<?php

use App\Models\Questao;
use Illuminate\Support\Facades\Route;
use OpenAI\Laravel\Facades\OpenAI;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/questao', function () {
    $result = OpenAI::completions()->create([
        'model' => 'text-davinci-003',
        'max_tokens' => 2060,
        "temperature" => 0.1,
        'prompt' => '
        
        ATUE COMO PROFESSOR E MELHORE A RESPOSTA DA AFIRMATIVA COM BASE NAS INFORÇAÕES ABAIXO (DENTRO DA RESPOTA FORMATE COM CODIGO HTML E CSS PARA MELHORAR A VISUALIZAÇÃO DO TEXTO):
        
        CONTEXTO: No que se refere ao Código Penal e ao Decreto n.º 37.042/1996, o qual aprova o Regulamento Disciplinar da Polícia Militar do Estado de Alagoas, julgue o item que se segue.
        AFIRMATIVA : A legítima defesa também poderá incidir nas hipóteses de agressão a direitos de terceiros.
       
        RESPOSTA DA AFIRMATIVA: ( 
            
        Gabarito: Certo.

        No que se refere ao Código Penal e ao Decreto n.º 37.042/1996, o qual aprova o Regulamento Disciplinar da Polícia Militar do Estado de Alagoas, julgue o item que se segue.
        A legítima defesa também poderá incidir nas hipóteses de agressão a direitos de terceiros.
        
        (CORRETA). A agressão injusta, atual ou iminente, deve ameaçar bem jurídico próprio ou de terceiro (CP, art. 25, caput). Qualquer bem jurídico pode ser protegido pela legítima defesa, pertencente àquele que se defende ou a terceira pessoa.    
       
        Legítima defesa
        Art. 25 - Entende-se em legítima defesa quem, usando moderadamente dos meios necessários, repele injusta agressão, atual ou iminente, a direito seu ou de outrem.
        )

        ',
    ]);

    echo $result['choices'][0]['text']; 
});


Route::get('/termos', function () {

    $result = OpenAI::completions()->create([
        'model' => 'text-davinci-003',
        'max_tokens' => 2060,
        "temperature" => 0.1,
        'prompt' => '
        
        ATUE COMO PROFESSOR E CRIE UM ARTIGO COMPLETO COM LINGUAGEM DE FACIL ENTENDIMENTO SOBRE O Assunto ABAIXO:
        FORMATE COM CODIGO HTML E CSS.
        SE POSSIVEL ORGANIZE EM TOPICOS.
        COLOQUE ARTIGOS DE LEIS SOBRE O ASSUNTO E GRIFE EM NEGRITO.
        GRIFE AS PARTES IMPORTANTE COM NEGRITO OU SUBILIADO.       
        Matéria: Direito Penal
        Assunto: Legítima Defesa

        ',
    ]);

    echo $result['choices'][0]['text']; 
});


Route::prefix('/jobs')->group(function () {
    Route::queueMonitor();
});
