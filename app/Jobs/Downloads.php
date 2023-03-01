<?php

namespace App\Jobs;

use App\Models\Cargo;
use App\Models\Comentario;
use Carbon\Carbon;
use DateTime;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use romanzipp\QueueMonitor\Traits\IsMonitored;

class Downloads implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use IsMonitored;

    protected $file_url;
    public $tries = 0;
    public $path;

    public function __construct($file_url, $path)
    {
        $this->file_url = $file_url;
        $this->path = $path;
    }


    public function handle()
    {
        try {
            $client = new Client();
            $response = $client->get(
                $this->file_url,
                ['headers' => getDefaultHeaders()]
            );

            if ($response->getStatusCode() == 200) {
                $fileContents = $response->getBody()->getContents();
                $fileLengthWeb = $response->getHeader('Content-Length');
                $fileName = basename($this->file_url);

                if (!Storage::disk('s3')->exists($this->path . "/" . $fileName) || Storage::disk('s3')->size($this->path . "/" . $fileName) !=  $fileLengthWeb[0]) {
                    Storage::disk('s3')->put($this->path . "/" . $fileName, $fileContents);
                    echo "Download - {$fileName} Realizado com Sucesso!" . PHP_EOL;
                }
            }

            sleep(getDelayDownload());
            
        } catch (\Exception $e) {
            $this->job->fail($e);
            echo $e->getMessage() . PHP_EOL;
        }
    }

    public function middleware()
    {
        return [
            new RateLimited('downloads')
        ];
    }
}
