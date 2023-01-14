<?php

use App\Models\Questao;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    //return view('welcome');

    $questaoModel = Questao::where('ext_id', 1)->first();
    echo "---";
    echo $questaoModel;

    if ($questaoModel) {
        echo "1";
    } else {
        echo "2";
    }
});



Route::prefix('/jobs')->group(function () {
    Route::queueMonitor();
});
