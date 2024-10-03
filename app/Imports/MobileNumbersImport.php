<?php

namespace App\Imports;

use App\Models\MobileNumber;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\Importable;

class MobileNumbersImport implements ToModel, WithChunkReading, WithBatchInserts
{
    use Importable;

    public function model(array $row)
    {
        Log::info('Processing row: ' . json_encode($row));

        // Check if the mobile number exists and is not empty
        if (isset($row[0]) && !empty($row[0])) { 
            return new MobileNumber([
                'mobile_number' => $row[0], 
            ]);
        } else {
            // Log error 
            Log::error('Missing mobile number for row: ' . json_encode($row));            
            return null; 
        }
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000; 
    }
}
