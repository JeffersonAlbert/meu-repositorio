<?php

namespace App\Services;

use App\Helpers\UploadFiles;

class UploadService
{
    public function uploadFiles($files, $type, $description)
    {
        $filesArray = [];
        foreach ($files as $index => $file)
        {
            $fileName = time().$file->getClientOriginalName();
            $filesArray[] = [
                'fileName' => $fileName,
                'fileType' => $type[$index],
                'fileDesc' => $description[$index] ?? null
            ];
            $r2 = new UploadFiles();
            $r2->uploadToR2(
                'uploads/'.$fileName,
                file_get_contents($file->getRealPath())
            );
        }
        return $filesArray;
    }

    public function mergeFiles($filesBd, $filesArray)
    {
        if(!isset($filesBd) or $filesBd == 'null'){
            return $filesArray;
        }

        foreach(json_decode($filesBd, true) as $index => $file){
            $filesArray[] = [
                'fileName' => $file['fileName'],
                'fileType' => $file['fileType'],
                'fileDesc' => $file['fileDesc']
            ];
        }
        return $filesArray;
    }
}
