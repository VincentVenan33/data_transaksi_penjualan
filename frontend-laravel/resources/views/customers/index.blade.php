@extends('layouts.app')

@section('title', 'Customers')

@section('content')
    <h2 class="mb-4">Daftar Pelanggan</h2>

    <a href="{{ route('customers.create') }}" class="btn btn-success mb-3">
        <i class="bi bi-plus-circle"></i> Tambah Pelanggan
    </a>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($customers['data'] ?? [] as $c)
                <tr>
                    <td>{{ $c['id'] }}</td>
                    <td>{{ $c['name'] }}</td>
                    <td>{{ $c['email'] }}</td>
                    <td>
                        <a href="{{ route('customers.edit', $c['id']) }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-pencil-square"></i> Edit
                        </a>
                        <form action="{{ url('/customers/'.$c['id']) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus customer ini?')">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Belum ada Pelanggan</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
