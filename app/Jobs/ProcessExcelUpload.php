<?php

namespace App\Jobs;

use App\Imports\MobileNumbersImport;
use Illuminate\Foundation\Bus\Dispatchable;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ProcessExcelUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $filePath;

    public $tries = 3;

    // Timeout for the job in seconds
    public $timeout = 120;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    public function handle()
    {
        try {
            // Import the Excel file
            Excel::import(new MobileNumbersImport(), $this->filePath);
            
            // Delete the file after processing
            Storage::delete($this->filePath);
            
            // Log success message
            Log::info('File uploaded successfully: ' . $this->filePath);
        } catch (Throwable $e) {
            // Log error message
            Log::error('Failed to process Excel file: ' . $e->getMessage());
            
            // Mark the job as failed
            $this->fail($e);
            
            //  delete the file on failure
            Storage::delete($this->filePath);
        }
    }

    public function failed(Throwable $exception)    
    {
        // Log error message for the failed job
        Log::error('Job failed: ' . $exception->getMessage());
        
        // Delete the file on failure
        Storage::delete($this->filePath);
    }
}
