<?php

namespace App\Http\Controllers\SYS;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessPdf;
use Aws\S3\S3Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Spatie\PdfToImage\Pdf;

class R2Controller extends Controller
{
    private $key;
    private $secret;
    private $region;
    private $bucket;
    private $endpoint;

    public function __construct()
    {
        $this->middleware('auth');
        $this->key = config('filesystems.disks.r2.key');
        $this->secret = config('filesystems.disks.r2.secret');
        $this->region = config('filesystems.disks.r2.region');
        $this->bucket = config('filesystems.disks.r2.bucket');
        $this->endpoint = config('filesystems.disks.r2.endpoint');
    }
    public function generateSignedUrl($filePath)
    {
        $s3Client = new S3Client([
            'region' => 'auto',
            'endpoint' => $this->endpoint,
            'version' => 'latest',
            'credentials' => [
                'key' => $this->key,
                'secret' => $this->secret,
            ],
        ]);

        $cmd = $s3Client->getCommand('GetObject', [
            'Bucket' => $this->bucket,
            'Key' => $filePath,
        ]);

        $request = $s3Client->createPresignedRequest($cmd, '+20 minutes');

        return (string) $request->getUri();
    }

    public function fetch($filepath)
    {
        $cacheKey = explode('/', $filepath);
        $key = count($cacheKey)-1;
        if(!Cache::has('blob_'.$cacheKey[$key])){
            $response = Storage::disk('r2')->get($filepath);
            $contentType =  Storage::disk('r2')->mimeType($filepath);
            Cache::put('blob_' . $cacheKey[$key], $response, now()->addWeek());
            Cache::put('content_type_' . $cacheKey[$key], $contentType, now()->addWeek());
        }else{
            $response = Cache::get('blob_'.$cacheKey[$key]);
            $contentType = Cache::get('content_type_'.$cacheKey[$key]);
        }

        return response($response)->header('Content-Type', $contentType);
    }

    public function getImage($filepath)
    {
        $response = ['blob' => Storage::disk('r2')->get($filepath), 'content_type' => Storage::disk('r2')->mimeType($filepath)];
        return $response;
    }

    public function save($response, $savePath)
    {
        file_put_contents($savePath, $response);
        return response("Arquivo salvo com sucesso em: " . $savePath, 200)
            ->header('Content-Type', 'text/plain');
    }

    public function fetchBase64($filepath)
    {
        $cacheKey = explode('/', $filepath);
        $key = count($cacheKey) - 1;
        $response = null;
        $contentType = null;

        if (!Cache::has('blob_' . $cacheKey[$key])) {
            $response = Storage::disk('r2')->get($filepath);
            $contentType = Storage::disk('r2')->mimeType($filepath);
            Cache::put('blob_' . $cacheKey[$key], $response, now()->addMinutes(10));
            Cache::put('content_type_' . $cacheKey[$key], $contentType, now()->addMinutes(10));
        } else {
            $response = Cache::get('blob_' . $cacheKey[$key]);
            $contentType = Cache::get('content_type_' . $cacheKey[$key]);
        }

        return response()->json([
            'base64' => base64_encode($response),
            'content_type' => $contentType,
        ]);
    }

    public function teste()
    {
        $put = Storage::disk('r2')->put('teste.txt', 'teste');
        $lala = Storage::disk('r2')->get('logo/1/1724016641logo.png');
        dd($lala, $put);
    }
}

