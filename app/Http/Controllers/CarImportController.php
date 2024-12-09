<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\CarsImport;
use Maatwebsite\Excel\Facades\Excel;

class CarImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new CarsImport, $request->file('file'));

        return redirect()->back()->with('success', 'Данные успешно импортированы.');
    }
}