<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessExcelUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ExcelfileUploader extends Controller
{
    public function uploadExcelFile(Request $request) 
    {
        // Validate the uploaded file..
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Store the uploaded file
        $filePath = $request->file('file')->store('uploads'); 

        // Dispatch a job to process the file
        ProcessExcelUpload::dispatch($filePath);

        // Return success response immediately after dispatching the job
        return response()->json(['message' => 'File uploaded successfully. Processing this file in  the background.'], 200);
    }
}
