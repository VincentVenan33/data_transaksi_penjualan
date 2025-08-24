<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{
    private $baseUrl = "http://localhost:8000/products";

    public function index(Request $request)
    {
        $page   = $request->input('page', 1);
        $limit  = $request->input('limit', 10);
        $category = $request->input('category');

        $url = "{$this->baseUrl}?page=$page&limit=$limit";
        if ($category) $url .= "&category=$category";

        $products = Http::get($url)->json();

        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $data = $request->only(['id', 'name', 'price', 'stock', 'category']);
        Http::post($this->baseUrl, $data);

        return redirect('/products')->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit($id)
    {
        $product = Http::get("http://localhost:8000/products/$id")->json();
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->only(['name', 'category', 'price', 'stock']);

        // Panggil API Node.js untuk update stok dan data
        Http::put("http://localhost:8000/products/$id", $data);

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil diperbarui');
    }
}
