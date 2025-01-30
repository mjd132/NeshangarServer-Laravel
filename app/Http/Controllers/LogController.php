<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;

class LogController extends Controller
{
    public function index()
    {
        $logPath = storage_path('logs/laravel.log');

        if (file_exists($logPath)) {
            $logs = File::get($logPath);
            $logLines = explode("\n", $logs);

            // Parse each log line into an array of [timestamp, level, message]
            $parsedLogs = [];
            foreach ($logLines as $line) {
                if (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] (\w+)\.(\w+): (.+)$/', $line, $matches)) {
                    $parsedLogs[] = [
                        'timestamp' => $matches[1],
                        'level' => $matches[3], // Log level (e.g., INFO, ERROR, DEBUG)
                        'message' => $matches[4],
                    ];
                } else {
                    $parsedLogs[] = ['timestamp' => '', 'level' => '', 'message' => $line];
                }
            }

            return view('logs', ['logs' => $parsedLogs]);
        } else {
            return response()->json(['message' => 'Log file does not exist.'], 404);
        }
    }

    public function clear()
    {
        $logPath = storage_path('logs/laravel.log');

        if (file_exists($logPath)) {
            file_put_contents($logPath, '');
            return response()->json(['message' => 'Logs cleared successfully!']);
        } else {
            return response()->json(['message' => 'Log file does not exist.'], 404);
        }
    }
}
