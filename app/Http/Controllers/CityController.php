<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RussiaCity;

class CityController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q');
        
        // Поиск городов по введенной строке
        $cities = RussiaCity::where('city', 'LIKE', "%{$query}%")->get();

        return response()->json($cities);
    }


}

