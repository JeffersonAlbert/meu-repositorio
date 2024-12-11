<?php

namespace App\Traits;

use App\Helpers\UploadFiles;

trait Files
{
    public $inputs = [];
    public $accountFiles = [];
    public $accountFilesType = [];
    public $accountFilesDescription = [];
    public $filesArray = [];
    public function addInput()
    {
        $this->inputs[] = '';
    }

    public function removeInput($index)
    {
        unset($this->inputs[$index]);
        $this->inputs = array_values($this->inputs);
    }

    public function uploadFiles()
    {
        $filesArray = [];
        foreach ($this->accountFiles as $index => $file)
        {
            $fileName = time().$file->getClientOriginalName();
            $filesArray[] = [
                'fileName' => $fileName,
                'fileType' => $this->accountFilesType[$index],
                'fileDesc' => $this->accountFilesDescription[$index] ?? null
            ];
            $r2 = new UploadFiles();
            $r2->uploadToR2(
                'uploads/'.$fileName,
                file_get_contents($file->getRealPath())
            );
        }
        $this->filesArray = $filesArray;
        return $filesArray;
    }
}
