<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class UploadFiles{

    public $path;
    public $bucket;
    public $endpoint;

    public function __construct() {
        $this->path = !empty($_ENV['AWS_ACCESS_KEY_ID']) ? $_ENV['AWS_BUCKET'] : 'uploads';
        $this->bucket = config('r2.bucket');
        $this->endpoint = config('r2.endpoint');
    }

    public function uploadNewFile($files){
        $i = 0;
        $arrayFileName = [];
        foreach ($files as $file) {
            $fileName = microtime(true) . $file->getClientOriginalName();
            usleep(500000);
            array_push($arrayFileName, $fileName);
            $file->move($this->path, $fileName);
            if (!file_exists($this->path."/{$fileName}")) {
                return [
                    'error' => "Algo de errado para salvar o arquivo verificar com o administrador"
                ];
            }
            $i++;
        }
        return $arrayFileName;
    }

    public function fileDescAndType($files, $type=null, $desc)
    {
        $i = 0;
        $fileDescAndType = [];
        foreach($files as $file){
            $fileDescAndType[] = [
                'fileName' => $file,
                'fileType' => is_null($type) ? null : $type[$i],
                'fileDesc' => $desc[$i]
            ];
            $i++;
        }
        return $fileDescAndType;
    }

    public function removeFile($array, $fileToRemove)
    {
        if(file_exists("{$this->path}/{$fileToRemove['fileName']}")){
            unlink("{$this->path}/{$fileToRemove['fileName']}");
        }

        return array_filter($array, function($file) use ($fileToRemove) {
            return !(
                $file['fileName'] === $fileToRemove['fileName'] &&
                $file['fileType'] === $fileToRemove['fileType'] &&
                $file['fileDesc'] === $fileToRemove['fileDesc']
            );
        });
    }

    public function uploadToR2($fileName, $content)
    {
       $uploadStatus = Storage::disk('r2')->put($fileName, $content);
       return $uploadStatus;
    }
}
