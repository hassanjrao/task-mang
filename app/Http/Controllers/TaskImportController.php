<?php

namespace App\Http\Controllers;

use App\Imports\TasksImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TaskImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xls,xlsx',
        ]);

        $createdBy = auth()->user();

        Excel::import(new TasksImport($createdBy), $request->file('file'));

        return response()->json(['message' => 'Imported successfully'],200);
    }
}
