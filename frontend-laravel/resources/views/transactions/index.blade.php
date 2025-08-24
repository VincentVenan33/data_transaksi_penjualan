@extends('layouts.app')

@section('title', 'Transaksi')

@section('content')
<h2 class="mb-4">Daftar Transaksi</h2>

<a href="{{ route('transactions.create') }}" class="btn btn-success mb-3">
    <i class="bi bi-plus-circle"></i> Tambah Transaksi
</a>

<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Produk</th>
            <th>Customer</th>
            <th>Qty</th>
            <th>Tanggal</th>
            {{-- <th>Aksi</th> --}}
        </tr>
    </thead>
    <tbody>
        @forelse($transactions['data'] ?? [] as $t)
            <tr>
                <td>{{ $t['id'] }}</td>
                <td>{{ $t['product_name'] }}</td>
                <td>{{ $t['customer_name'] }}</td>
                <td>{{ $t['quantity'] }}</td>
                <td>{{ \Carbon\Carbon::parse($t['created_at'])->locale('id')->isoFormat('D MMMM Y ~ HH:mm:ss') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center">Belum ada transaksi</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection
