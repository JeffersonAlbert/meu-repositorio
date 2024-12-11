<?php

namespace App\Services;

class LogsNumber
{
    public function __construct()
    {
        //
    }

    public function saveLog($data)
    {
         \App\Models\LogsNumber::create($data);
        return;
    }
}
