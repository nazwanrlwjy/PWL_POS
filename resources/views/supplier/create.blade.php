@extends('layouts.template')

@section('content')
    <div class="container">
        <h2>Tambah Supplier</h2>
        <form action="{{ url('supplier') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Kode Supplier</label>
                <input type="text" name="supplier_kode" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Nama Supplier</label>
                <input type="text" name="supplier_nama" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Alamat</label>
                <textarea name="supplier_alamat" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label>Telepon</label>
                <input type="text" name="supplier_telp" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
@endsection