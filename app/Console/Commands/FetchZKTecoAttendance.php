<?php

namespace App\Console\Commands;

use App\Http\Controllers\AttendanceController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Rats\Zkteco\Lib\ZKTeco;

class FetchZKTecoAttendance extends Command
{
    protected $signature = 'fetch:zkteco-attendance';
    protected $description = 'Fetch attendance records from ZKTeco device';


    public function handle()
    {
        $attendance =new AttendanceController();
        $attendance->index();
    }
}
