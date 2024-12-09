<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Advert;
use Illuminate\Support\Facades\Auth;

class MarketAnalysisController extends Controller
{
    public function index(Request $request)
    {
        $productName = $request->input('part_name_or_number');
        $brand = $request->input('brand');

        // Проверяем, была ли отправлена форма
        if ($request->has('part_name_or_number') && $request->has('brand')) {
            // Получаем авторизованного пользователя
            $user = Auth::user();

            // Ищем товары авторизованного пользователя
            $userAdverts = Advert::where('user_id', $user->id)
                ->where(function ($query) use ($productName, $brand) {
                    $query->where('product_name', 'like', '%' . $productName . '%')
                        ->orWhere('art_number', 'like', '%' . $productName . '%');
                })
                ->where('brand', 'like', '%' . $brand . '%')
                ->get();

            // Ищем товары конкурентов
            $competitorAdverts = Advert::where('user_id', '!=', $user->id)
                ->where(function ($query) use ($productName, $brand) {
                    $query->where('product_name', 'like', '%' . $productName . '%')
                        ->orWhere('art_number', 'like', '%' . $productName . '%');
                })
                ->where('brand', 'like', '%' . $brand . '%')
                ->get();

            // Вычисляем минимальную, среднюю и максимальную цену
            $prices = Advert::where(function ($query) use ($productName, $brand) {
                    $query->where('product_name', 'like', '%' . $productName . '%')
                        ->orWhere('art_number', 'like', '%' . $productName . '%');
                })
                ->where('brand', 'like', '%' . $brand . '%')
                ->pluck('price');

            $minPrice = $prices->min();
            $maxPrice = $prices->max();
            $avgPrice = $prices->avg();

            return view('market', compact('userAdverts', 'competitorAdverts', 'productName', 'brand', 'minPrice', 'maxPrice', 'avgPrice'));
        }

        // Если форма не была отправлена, возвращаем пустую страницу
        return view('market', ['userAdverts' => collect(), 'competitorAdverts' => collect(), 'productName' => null, 'brand' => null, 'minPrice' => null, 'maxPrice' => null, 'avgPrice' => null]);
    }
}