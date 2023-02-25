<?php

use App\Models\Concurso;
use App\Models\Edital;
use App\Models\Escolaridade;
use App\Models\Orgao;
use App\Models\Questao;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use OpenAI\Laravel\Facades\OpenAI;
use App\Services\StorageService;
use GuzzleHttp\Client;

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
Route::get('/aws', function () {
  
    $file_url = 'https://www.tecconcursos.com.br/download/edital-n-1-agermt-de-1-de-fevereiro-de-2023-ager-mt-2023'; // URL do arquivo a ser baixado

    $path = 'teste';
    try {
        $client = new Client();
        $response = $client->get($file_url, []);

        if ($response->getStatusCode() == 200) {
            $fileContents = $response->getBody()->getContents();
            $fileLengthWeb = $response->getHeader('Content-Length');
            $fileType = $response->getHeader('Content-Type');

            $extension = '';
            if ($fileType[0] == "application/pdf") {
                $extension = ".pdf";
            } elseif ($fileType[0] == "image/jpeg" || $fileType[0] == "image/pjpeg" || $fileType[0] == "image/jpeg" || $fileType[0] == "image/pjpeg") {
                $extension = ".jpg";
            } elseif ($fileType[0] == "image/png") {
                $extension = ".png";
            } elseif ($fileType == "image/gif") {
                $extension = ".gif";
            } elseif ($fileType[0] == "application/zip") {
                $extension = ".zip";
            } elseif ($fileType[0] == "application/x-rar-compressed") {
                $extension = ".rar";
            } elseif ($fileType[0] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document") {
                $extension = ".docx";
            } elseif ($fileType[0] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
                $extension = ".xlsx";
            } elseif ($fileType[0] == "application/vnd.openxmlformats-officedocument.presentationml.presentation") {
                $extension = ".pptx";
            } else {
                $extension = ".jpgx";
            }

            $fileName = basename($file_url);
            echo $fileName;

            if (!Storage::disk('s3')->exists($path . "/" . $fileName) || Storage::disk('s3')->size($path . "/" . $fileName) !=  $fileLengthWeb[0]) {
                Storage::disk('s3')->put($path . "/" . $fileName, $fileContents);
            }
        }
    } catch (\Exception $e) {
        $this->job->fail($e);
        echo $e->getMessage() . PHP_EOL;
    }
});


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

Route::get('/l', function () {
    $endPoint = "http://localhost/mediawiki/api.php";
    $login_Token = getLoginToken($endPoint); // Step 1
    loginRequest($login_Token); // Step 2
    $csrf_Token = getCSRFToken($endPoint); // Step 3
    editRequest($csrf_Token, $endPoint); // Step 4
});

Route::prefix('/jobs')->group(function () {
    Route::queueMonitor();
});

function getLoginToken($endPoint)
{
    $params1 = [
        "action" => "query",
        "meta" => "tokens",
        "type" => "login",
        "format" => "json"
    ];

    $url = $endPoint . "?" . http_build_query($params1);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIEJAR, "/tmp/cookie.txt");
    curl_setopt($ch, CURLOPT_COOKIEFILE, "/tmp/cookie.txt");

    $output = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($output, true);
    return $result["query"]["tokens"]["logintoken"];
}

function loginRequest($logintoken)
{
    global $endPoint;

    $params2 = [
        "action" => "login",
        "lgname" => "Otavio.gatz",
        "lgpassword" => "442800442800",
        "lgtoken" => $logintoken,
        "format" => "json"
    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $endPoint);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params2));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIEJAR, "/tmp/cookie.txt");
    curl_setopt($ch, CURLOPT_COOKIEFILE, "/tmp/cookie.txt");

    $output = curl_exec($ch);
    curl_close($ch);

    echo($output);
}

// Step 3: GET request to fetch CSRF token
function getCSRFToken($endPoint)
{
    $params3 = [
        "action" => "query",
        "meta" => "tokens",
        "format" => "json"
    ];

    $url = $endPoint . "?" . http_build_query($params3);

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIEJAR, "cookie.txt");
    curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");

    $output = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($output, true);
    return $result["query"]["tokens"]["csrftoken"];
}

// Step 4: POST request to edit a page
function editRequest($csrftoken, $endPoint)
{
    $params4 = [
        "action" => "edit",
        "title" => "Questão10",
        "appendtext" => "{{Questão
| pergunta        = 1Qual é a capital do Brasil?
| resposta        = 1Brasília
| categoria       = 1Geografia
| dificuldade     = 1Fácil
| ano             = 12020
}}",
        "token" => $csrftoken,
        "format" => "json",
        "createonly" =>"true"
    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $endPoint);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params4));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIEJAR, "cookie.txt");
    curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");

    $output = curl_exec($ch);
    curl_close($ch);

    echo($output);
}
