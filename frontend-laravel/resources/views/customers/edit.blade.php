@extends('layouts.app')

@section('title', 'Edit Customer')

@section('content')
    <h2 class="mb-4">Edit Pelangan</h2>

    <form action="{{ url('/customers/'.$customer['id']) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">ID Pelanggan</label>
            <input type="text" class="form-control" value="{{ $customer['id'] }}" readonly>
        </div>

        <div class="mb-3">
            <label for="name" class="form-label">Nama</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $customer['name'] }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ $customer['email'] }}" required>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> Simpan Perubahan
        </button>
        <a href="{{ url('/customers') }}" class="btn btn-secondary">Batal</a>
    </form>
@endsection
