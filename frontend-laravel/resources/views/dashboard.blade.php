@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<h2 class="mb-4">Dashboard Manajemen Inventory</h2>

<form method="GET" action="{{ url('/') }}" class="mb-4 d-flex gap-2">
    <input type="date" name="start" value="{{ request('start') }}" class="form-control">
    <input type="date" name="end" value="{{ request('end') }}" class="form-control">
    <button type="submit" class="btn btn-primary"><i class="bi bi-funnel-fill"></i> Filter</button>
</form>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white p-3">
            <h6>Total Produk</h6>
            <h3>{{ $products['total'] ?? 0 }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white p-3">
            <h6>Total Transaksi</h6>
            <h3>{{ $transactions['total'] ?? 0 }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-dark p-3">
            <h6>Nilai Inventori</h6>
            <h3>Rp {{ number_format($inventoryValue['totalValue'] ?? 0,0,',','.') }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white p-3">
            <h6>Produk Stok Rendah</h6>
            <h3>{{ $lowStock['total'] ?? 0 }}</h3>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6 d-flex">
        <div class="card p-3 w-100">
            <h5>Penjualan Per Bulan</h5>
            <canvas id="chartMonthlySales" height="600"></canvas>
        </div>
    </div>

    <div class="col-md-6 d-flex">
        <div class="card p-3 w-100">
            <h5>Penjualan Per Kategori Barang</h5>
            <canvas id="chartCategorySales" height="600"></canvas>
        </div>
    </div>
</div>

<div class="card p-3 mb-4">
    <h5>10 Produk Terlaris</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Kategori</th>
                <th>Total Terjual</th>
                <th>Nilai Penjualan (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topProducts as $p)
            <tr>
                <td>{{ $p['name'] }}</td>
                <td>{{ $p['category'] }}</td>
                <td>{{ $p['total_sold'] }}</td>
                <td>Rp {{ number_format($p['total_value'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const monthlySalesCtx = document.getElementById('chartMonthlySales');
new Chart(monthlySalesCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($monthlySales['labels']) !!},
        datasets: [{
            label: 'Penjualan (Rp)',
            data: {!! json_encode($monthlySales['data']) !!},
            borderColor: 'rgba(54,162,235,1)',
            backgroundColor: 'rgba(54,162,235,0.2)',
            fill: true
        }]
    }
});

const categorySalesCtx = document.getElementById('chartCategorySales');
new Chart(categorySalesCtx, {
    type: 'pie',
    data: {
        labels: {!! json_encode($categorySales['labels']) !!},
        datasets: [{
            data: {!! json_encode($categorySales['data']) !!},
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF']
        }]
    }
});
</script>

@endsection
