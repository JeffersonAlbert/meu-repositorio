<?php

namespace App\Console\Commands;

use App\Helpers\FormatUtils;
use App\Models\ContasReceber;
use App\Models\Processo;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class AddTraceCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-trace-code';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create trace_code in lines with trace_code == null';

    /**
     * Execute the console command.
     */
    public function __construct()
    {
        parent::__construct();
    }
    public function handle()
    {
        $registros  = Processo::whereNull('trace_code')->get();
        foreach($registros as $registro){
            $registro->trace_code = FormatUtils::traceCode(1);
            $registro->save();
        }

        $registros = ContasReceber::where('trace_code', '000000000')->get();
        foreach($registros as $registro){
            $registro->trace_code = FormatUtils::traceCode(1);
            $registro->save();
        }
    }
}
