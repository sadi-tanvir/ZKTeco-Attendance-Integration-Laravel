<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\InOut;
use App\Models\ZktecoDevices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Rats\Zkteco\Lib\ZKTeco;
use Illuminate\Support\Facades\Http;



class AttendanceController extends Controller
{

    private $zk;

    public function index()
    {
        try {
            // Retrieve the IP address from the request
            $ip_address = '192.168.0.99';
            $port = 4370; // Default to 4370 if not provided
            $apiUrl = env('CMS_API_URL');

            // Initialize ZKTeco with the provided IP address and port
            $this->zk = new ZKTeco($ip_address, $port);

            if ($this->zk->connect()) {
                // Connection successful
                Log::info('Device connection successful');
            } else {
                Log::info('Failed to connect to ZKTeco device');
                return;
            }

            // Proceed with the rest of your logic
            $attendanceRecords = $this->zk->getAttendance();

            // Log::info($attendanceRecords);

            foreach ($attendanceRecords as $record) {
                $timestamp = $record['timestamp'];
                $timestamp_date = Carbon::parse($timestamp)->format('Y-m-d');
                $string = substr($this->zk->serialNumber(), strpos($this->zk->serialNumber(), "=") + 1);
                $serialNumber = preg_replace('/null|\0/i', '', $string);

                $exists = Attendance::where('uid', $record['uid'])
                    ->where('user_id', $record['id'])
                    ->where('timestamp', $timestamp)
                    ->exists();

                if (!$exists) {
                    Attendance::create([
                        'uid' => $record['uid'],
                        'user_id' => $record['id'],
                        'state' => $record['state'],
                        'timestamp' => $timestamp,
                        'type' => $record['type'],
                    ]);

                    $exists_check = InOut::where('user_id', $record['id'])
                        ->where('date', $timestamp_date)
                        ->exists();

                    if ($exists_check) {
                        $last_in = InOut::where('user_id', $record['id'])
                            ->where('date', $timestamp_date)
                            ->first()->in_time;
                        InOut::where('user_id', $record['id'])
                            ->where('date', $timestamp_date)->update([
                                    'user_id' => $record['id'],
                                    'in_time' => $last_in,
                                    'out_time' => $record['timestamp'],
                                    'time_calc' => DB::raw('TIMESTAMPDIFF(SECOND, in_time, out_time)'),
                                    'date' => Carbon::parse($record['timestamp'])->format('Y-m-d')
                                ]);


                        $response = Http::post($apiUrl, [
                            'student_id' => $record['id'],
                            'in_time' => $last_in,
                            'out_time' => $record['timestamp'],
                            'machine_no' => $serialNumber,
                            'date' => Carbon::parse($record['timestamp'])->format('Y-m-d')
                        ]);
                        // Log::info('Create Response status: ' . $response->status());
                        // Log::info('Create Response body: ' . $response->body());

                    } else {
                        InOut::create([
                            'user_id' => $record['id'],
                            'in_time' => $record['timestamp'],
                            'out_time' => null,
                            'time_calc' => 0,
                            'date' => Carbon::parse($record['timestamp'])->format('Y-m-d')
                        ]);
                        $response = Http::post($apiUrl, [
                            'student_id' => $record['id'],
                            'in_time' => $record['timestamp'],
                            'out_time' => null,
                            'machine_no' => $serialNumber,
                            'date' => Carbon::parse($record['timestamp'])->format('Y-m-d')
                        ]);
                        // Log::info('Create Response status: ' . $response->status());
                        // Log::info('Create Response body: ' . $response->body());
                    }
                } else {
                    // Log::info('Duplicate attendance record found: ', $record);
                }
            }
        } catch (\Exception $e) {
            // Log::error('Error processing attendance: ' . $e->getMessage());
        }
    }


}





