<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessExcelUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExcelfileUploader extends Controller
{
    public function uploadExcelFile(Request $request) {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls,csv | max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        //store the uplaoded file 
        $file = $request->file('file')->store('uploads');

        //dispatch a job to process the file
       $job = ProcessExcelUpload::dispatch($file);
       if($job){
        return response()->json(['message' => 'File uploaded successfully']);
       }
       else{
        return response()->json(['error' => 'Failed to upload file']);
       }

    }
}
