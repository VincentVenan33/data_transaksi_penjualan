@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')
<h2 class="mb-4">Tambah Produk</h2>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<form action="{{ url('/products') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label for="name" class="form-label">Nama Produk</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>

    <div class="mb-3">
        <label for="category" class="form-label">Kategori</label>
        <input type="text" class="form-control" id="category" name="category" required>
    </div>

    <div class="mb-3">
        <label for="price" class="form-label">Harga</label>
        <input type="number" class="form-control" id="price" name="price" min="0" required>
    </div>

    <div class="mb-3">
        <label for="stock" class="form-label">Stok</label>
        <input type="number" class="form-control" id="stock" name="stock" min="0" required>
    </div>

    <button type="submit" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Produk
    </button>
    <a href="{{ url('/products') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
