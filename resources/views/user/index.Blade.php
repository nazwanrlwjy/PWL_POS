@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <a href="{{ route('user.create') }}" class="btn btn-sm btn-primary mt-1">Tambah</a>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <table class="table table-striped table-bordered table-hover table-sm" id="table_user">
                <thead>
                    <tr>
                        <th style=>ID</th>
                        <th>Username</th>
                        <th>Nama</th>
                        <th>Level Pengguna</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')
    <script>
        $(document).ready(function () {
            var dataUser = $('#table_user').DataTable({
                serverSide : true,
                ajax : {
                    url : "{{ route('user.list') }}",
                    dataType : 'json',
                    type : 'get',
                },
                columns : [
                    { data : "DT_RowIndex", className : "text-center", orderable : false, searchable : false},
                    { data : "username", className : "", orderable : true, searchable : true},
                    { data : "name", className : "", orderable : false, searchable : false},
                    { data : "level.level_name", className : "", orderable : false, searchable : false},
                    { data : "aksi", className : "", orderable : false, searchable : false},
                ],
            });
        });
    </script>
@endpush