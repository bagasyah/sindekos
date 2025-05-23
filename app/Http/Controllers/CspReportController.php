<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CspReportController extends Controller
{
    public function handleReport(Request $request)
    {
        // Proses laporan CSP di sini
        // Misalnya, simpan ke log atau database
        \Log::info('CSP Report: ', $request->all());
        
        return response()->json(['status' => 'success'], 200);
    }
}