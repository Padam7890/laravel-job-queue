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

        if (isset($row[0]) && !empty($row[0])) { 
            return new MobileNumber([
                'mobile_number' => $row[0], 
            ]);
        }

        Log::error('Missing mobile number for row: ' . json_encode($row));
        //return response
        return response()->json(['error' => 'Missing mobile number for row: ' . json_encode($row)]);
    }


    public function batchSize(): int
    {
         // Insert 1000 rows per batch
        return 1000;
    }

    public function chunkSize(): int
    {
        // Process 1000 rows per chunk
        return 1000; 
    }
}
