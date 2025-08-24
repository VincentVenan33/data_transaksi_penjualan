<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TransactionController extends Controller
{
    private $baseUrl = "http://localhost:8000";

    public function index(Request $request)
    {
        $transactions = Http::get("{$this->baseUrl}/transactions")->json();
        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        // Ambil data produk & customer dari API
        $products = Http::get("{$this->baseUrl}/products")->json();
        $customers = Http::get("{$this->baseUrl}/customers")->json();

        return view('transactions.create', compact('products', 'customers'));
    }

    public function store(Request $request)
    {
        $data = $request->only(['productId', 'customerId', 'quantity', 'type']);
        Http::post("{$this->baseUrl}/transactions", $data);
        return redirect('/transactions')->with('success', 'Transaksi berhasil ditambahkan');
    }

    public function edit($id)
    {
        $transaction = Http::get("{$this->baseUrl}/transactions/{$id}")->json();
        $products = Http::get("{$this->baseUrl}/products")->json();
        $customers = Http::get("{$this->baseUrl}/customers")->json();

        return view('transactions.edit', compact('transaction', 'products', 'customers'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->only(['productId', 'customerId', 'quantity', 'type']);
        Http::put("{$this->baseUrl}/transactions/{$id}", $data);
        return redirect('/transactions')->with('success', 'Transaksi berhasil diperbarui');
    }

    public function destroy($id)
    {
        Http::delete("{$this->baseUrl}/transactions/{$id}");
        return redirect('/transactions')->with('success', 'Transaksi berhasil dihapus');
    }
}
