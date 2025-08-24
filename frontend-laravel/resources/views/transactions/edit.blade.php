@extends('layouts.app')

@section('title', 'Edit Transaksi')

@section('content')
<h2 class="mb-4">Edit Transaksi</h2>

<form action="{{ url('/transactions/'.$transaction['id']) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="id" class="form-label">Kode Transaksi</label>
        <input type="text" id="id" class="form-control" value="{{ $transaction['id'] }}" readonly>
    </div>

    <div class="mb-3">
        <label for="productId" class="form-label">Produk</label>
        <select name="productId" id="productId" class="form-select" required>
            @foreach($products['data'] ?? [] as $p)
                <option value="{{ $p['id'] }}" data-stock="{{ $p['stock'] }}"
                    @if($p['id'] == $transaction['productId']) selected @endif>
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
                <option value="{{ $c['id'] }}" @if($c['id'] == $transaction['customerId']) selected @endif>
                    {{ $c['name'] }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="quantity" class="form-label">Jumlah</label>
        <input type="number" name="quantity" id="quantity" class="form-control"
            value="{{ $transaction['quantity'] }}" required min="1">
    </div>

    <input type="hidden" name="type" value="OUT">

    <button type="submit" class="btn btn-primary">
        <i class="bi bi-save"></i> Simpan Perubahan
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

    // Tampilkan stok saat halaman load
    updateStock();
</script>
@endsection
