@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Data Supplier</h3>
            <div class="card-tools">
                <!-- Tambah Tombol Import & Ajax -->
                <button onclick="modalAction('{{ url('/supplier/import') }}')" class="btn btn-info btn-sm mt-1">Import Supplier</button>
                <a href="{{ url('/supplier/create') }}" class="btn btn-primary btn-sm mt-1">Tambah Data</a>
                <button onclick="modalAction('{{ url('/supplier/create_ajax') }}')" class="btn btn-success btn-sm mt-1">Tambah Data (Ajax)</button>
            </div>
        </div>
        <div class="card-body">
            <!-- Filter -->
            <div id="filter" class="form-horizontal filter-date p-2 border-bottom mb-2">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group form-group-sm row text-sm mb-0">
                            <label for="filter_kode" class="col-md-1 col-form-label">Filter</label>
                            <div class="col-md-3">
                                <input type="text" name="filter_kode" class="form-control form-control-sm filter_kode" placeholder="Kode Supplier...">
                                <small class="form-text text-muted">Filter berdasarkan kode supplier</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table class="table table-bordered table-striped table-hover table-sm" id="table_supplier">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kode supplier</th>
                        <th>Nama Supplier</th>
                        <th>Telepon</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
    </div>
@endsection

@push('js')
<script>
    function modalAction(url = '') {
        $('#myModal').load(url, function() {
            $('#myModal').modal('show');
        });
    }

    var dataSupplier;
$(document).ready(function () {
    dataSupplier = $('#table_supplier').DataTable({
        serverSide: true,
        ajax: {
            url: "{{ url('supplier/list') }}",
            type: "POST",
            dataType: "json",
            data: function (d) {
                d.filter_kode = $('.filter_kode').val(); // filter dikirim ke server
            }
        },
        columns: [
            { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
            { data: "supplier_kode", orderable: true, searchable: true },
            { data: "supplier_nama", orderable: true, searchable: true },
            { data: "supplier_telp", orderable: true, searchable: true },
            { data: "supplier_alamat", orderable: false, searchable: false },
            { data: "aksi", orderable: false, searchable: false }
        ]
    });

    // Trigger filter input
    $('.filter_kode').on('input', function () {
        dataSupplier.draw();
    });
});

    
</script>
@endpush