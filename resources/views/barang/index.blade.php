@extends('layouts.template')

@section('content')
    <style>
        th, td {
            vertical-align: middle !important;
            white-space: nowrap;
        }

        th:first-child,
        td:first-child {
            width: 40px;
            text-align: center;
        }

        th:nth-child(2),
        td:nth-child(2) {
            width: 120px;
        }

        th:nth-child(3),
        td:nth-child(3) {
            width: 150px;
        }

        th:nth-child(4),
        td:nth-child(4) {
            width: 100px;
        }

        th:nth-child(5),
        td:nth-child(5),
        th:nth-child(6),
        td:nth-child(6) {
            text-align: right;
            width: 100px;
        }

        th:last-child,
        td:last-child {
            text-align: center;
            width: 90px;
        }
    </style>

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('barang/create') }}">Tambah</a>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Filter:</label>
                        <div class="col-3">
                            <select class="form-control" id="kategori_id" name="kategori_id">
                                <option value="">- Semua -</option>
                                @foreach($kategori as $item)
                                    <option value="{{ $item->kategori_id }}">{{ $item->nama_kategori}}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Kategori Barang</small>
                        </div>
                    </div>
                </div>
            </div>

            <table class="table table-bordered table-striped table-hover table-sm text-nowrap" id="table_barang">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        var dataBarang = $('#table_barang').DataTable({
            serverSide: true,
            ajax: {
                "url": "{{ url('barang/list') }}",
                "dataType": "json",
                "type": "POST",
                "data": function (d) {
                    d.kategori_id = $('#kategori_id').val();
                }
            },
            columns: [
                { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                { data: "barang_kode", orderable: true, searchable: true },
                { data: "barang_nama", orderable: true, searchable: true },
                { data: "kategori.nama_kategori", orderable: false, searchable: false },
                { data: "harga_beli", orderable: true, searchable: false, className: "text-right" },
                { data: "harga_jual", orderable: true, searchable: false, className: "text-right" },
                { data: "aksi", orderable: false, searchable: false }
            ]
        });

        $('#kategori_id').on('change', function() {
            dataBarang.ajax.reload();
        });
    });
</script>
@endpush
