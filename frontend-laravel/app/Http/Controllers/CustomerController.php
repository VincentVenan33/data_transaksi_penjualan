<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CustomerController extends Controller
{
    private $baseUrl = "http://localhost:8000/customers";

    public function index()
    {
        $customers = Http::get($this->baseUrl)->json();
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $data = $request->only(['name','email']);
        Http::post($this->baseUrl, $data);
        return redirect('/customers')->with('success', 'Customer berhasil ditambahkan');
    }

    public function edit($id)
    {
        $customer = Http::get("{$this->baseUrl}/$id")->json();
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->only(['name','email']);
        Http::put("{$this->baseUrl}/$id", $data);
        return redirect('/customers')->with('success', 'Customer berhasil diperbarui');
    }

    public function destroy($id)
    {
        Http::delete("{$this->baseUrl}/$id");
        return redirect('/customers')->with('success', 'Customer berhasil dihapus');
    }
}