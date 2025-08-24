@extends('layouts.app')

@section('title', 'Produk')

@section('content')
<h2 class="mb-4">Daftar Produk</h2>

<a href="{{ route('products.create') }}" class="btn btn-success mb-3">
    <i class="bi bi-plus-circle"></i> Tambah Produk
</a>

<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Kategori</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($products['data'] ?? [] as $p)
            <tr>
                <td>{{ $p['id'] }}</td>
                <td>{{ $p['name'] }}</td>
                <td>{{ $p['category'] }}</td>
                <td>Rp {{ number_format($p['price'],0,',','.') }}</td>
                <td>{{ $p['stock'] }}</td>
                <td>
                    <a href="{{ route('products.edit', $p['id']) }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-pencil-square"></i> Edit
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">Belum ada produk</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection
