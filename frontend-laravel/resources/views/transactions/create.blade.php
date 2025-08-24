@extends('layouts.app')

@section('title', 'Tambah Transaksi')

@section('content')
<h2 class="mb-4">Tambah Transaksi</h2>

<form action="{{ url('/transactions') }}" method="POST">
    @csrf

    <div class="mb-3">
    <label for="productId" class="form-label">Produk</label>
    <select name="productId" id="productId" class="form-select" required>
        @foreach($products['data'] ?? [] as $p)
            <option value="{{ $p['id'] }}" data-stock="{{ $p['stock'] }}">
                {{ $p['name'] }}
            </option>
        @endforeach
    </select>
    </div>

    <div class="mb-3">
        <label for="stockDisplay" class="form-label">Stok Tersedia</label>
        <input type="text" id="stockDisplay" class="form-control" readonly>
    </div>

    <div class="mb-3">
        <label for="customerId" class="form-label">Customer</label>
        <select name="customerId" id="customerId" class="form-select" required>
            @foreach($customers['data'] ?? [] as $c)
                <option value="{{ $c['id'] }}">{{ $c['name'] }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="quantity" class="form-label">Jumlah</label>
        <input type="number" name="quantity" id="quantity" class="form-control" required min="1">
    </div>

    <input type="hidden" name="type" value="OUT">

    <button type="submit" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Pelanggan
        </button>
    <a href="{{ url('/transactions') }}" class="btn btn-secondary">Batal</a>
</form>
<script>
    const productSelect = document.getElementById('productId');
    const stockDisplay = document.getElementById('stockDisplay');

    function updateStock() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const stock = selectedOption.getAttribute('data-stock');
        stockDisplay.value = stock;
    }

    productSelect.addEventListener('change', updateStock);

    // Inisialisasi saat halaman pertama kali load
    updateStock();
</script>

@endsection
