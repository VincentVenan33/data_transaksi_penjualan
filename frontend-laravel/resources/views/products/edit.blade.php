@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<h2 class="mb-4">Edit Produk</h2>

<form action="{{ route('products.update', $product['id']) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">ID Produk</label>
        <input type="text" class="form-control" value="{{ $product['id'] }}" readonly>
    </div>

    <div class="mb-3">
        <label class="form-label">Nama Produk</label>
        <input type="text" name="name" class="form-control" value="{{ $product['name'] }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Kategori</label>
        <input type="text" name="category" class="form-control" value="{{ $product['category'] }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Harga</label>
        <input type="number" name="price" class="form-control" value="{{ $product['price'] }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Stok</label>
        <input type="number" name="stock" class="form-control" value="{{ $product['stock'] }}" required>
    </div>

    <button type="submit" class="btn btn-primary">
        <i class="bi bi-save"></i> Simpan Perubahan
    </button>
    <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
