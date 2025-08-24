@extends('layouts.app')

@section('title', 'Tambah Customer')

@section('content')
    <h2 class="mb-4">Tambah Pelanggan</h2>

    <form action="{{ url('/customers') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nama</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Pelanggan
        </button>
        <a href="{{ url('/customers') }}" class="btn btn-secondary">Batal</a>
    </form>
@endsection
