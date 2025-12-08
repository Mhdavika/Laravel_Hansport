<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');

        // Cari berdasarkan nama produk
        $results = Product::where('name', 'LIKE', '%' . $query . '%')->get();

        return view('frontend.search.results', [
            'query' => $query,
            'results' => $results,
        ]);
    }
}
