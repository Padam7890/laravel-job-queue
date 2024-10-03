<?php
namespace App\Jobs;

use App\Imports\MobileNumbersImport;
use App\Models\MobileNumber;
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

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    public function handle()
    {
        try {
           $saveExcell =  Excel::import(new MobileNumbersImport(), $this->filePath);
           if($saveExcell){
            // delete the file after processing
            Storage::delete($this->filePath);
            return response()->json(['message' => 'File uploaded successfully']);
           }
        } catch (Throwable $e) {
            Log::error('Failed to process Excel file: ' . $e->getMessage());
            $this->fail($e); // Mark the job as failed
            return response()->json(['error' => 'Failed to process Excel file']);
        }
    }

    public function failed(Throwable $exception)
    {
        Log::error('Job failed: ' . $exception->getMessage());
        return response()->json(['error' => 'Job failed']);
    }
}
