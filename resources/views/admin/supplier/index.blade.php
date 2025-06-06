@extends('layouts.app')

@section('title', 'Data Supplier')

@section('content')
    <!-- Content Header (Page header) -->
    <div id="loadingSpinner" style="display: flex; align-items: center; justify-content: center; height: 100vh;">
        <i class="fas fa-spinner fa-spin" style="font-size: 3rem;"></i>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(function() {
                document.getElementById("loadingSpinner").style.display = "none";
                document.getElementById("mainContent").style.display = "block";
                document.getElementById("mainContentSection").style.display = "block";
            }, 100); // Adjust the delay time as needed
        });
    </script>

    <!-- Content Header (Page header) -->
    <div class="content-header" style="display: none;" id="mainContent">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                </div><!-- /.col -->
                <div class="col-sm-6">
                   
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content" style="display: none;" id="mainContentSection">
        <div class="container-fluid">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5>
                        <i class="icon fas fa-check"></i> Berhasil!
                    </h5>
                    {{ session('success') }}
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Supplier</h3>
                    <div class="float-right">
                        @if (auth()->user()->menufiturs()->where('nama', 'Data Supplier')->wherePivot('can_create', 1)->exists())
                            <a href="{{ url('admin/supplier/create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Tambah
                            </a>
                        @endif
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="datatables66" class="table table-bordered table-striped table-hover" style="font-size: 13px">
                        <thead class="thead-dark">
                            <tr>
                                <th class="text-center">No</th>
                                <th>Kode</th>
                                <th>Nama Supplier</th>
                                <th>Nama</th>
                                <th>Telepon</th>
                                <th class="text-center">Qr Code</th>
                                <th class="text-center" width="90">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($suppliers as $supplier)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $supplier->kode_supplier }}</td>
                                    <td>{{ $supplier->nama_supp }}</td>
                                    <td>{{ $supplier->nama_person }}</td>
                                    <td>{{ $supplier->telp }}</td>
                                    <td data-toggle="modal" data-target="#modal-qrcode-{{ $supplier->id }}"
                                        style="text-align: center;">
                                        <div style="display: inline-block;">
                                            {!! DNS2D::getBarcodeHTML("$supplier->qrcode_supplier", 'QRCODE', 2, 2) !!}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if (auth()->user()->menufiturs()->where('nama', 'Data Supplier')->wherePivot('can_show', 1)->exists())
                                            <a href="{{ url('admin/supplier/' . $supplier->id) }}"
                                                class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endif
                                        @if (auth()->user()->menufiturs()->where('nama', 'Data Supplier')->wherePivot('can_update', 1)->exists())
                                            <a href="{{ url('admin/supplier/' . $supplier->id . '/edit') }}"
                                                class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                        @if (auth()->user()->menufiturs()->where('nama', 'Data Supplier')->wherePivot('can_delete', 1)->exists())
                                            <button type="submit" class="btn btn-danger btn-sm" data-toggle="modal"
                                                data-target="#modal-hapus-{{ $supplier->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                <div class="modal fade" id="modal-hapus-{{ $supplier->id }}">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Hapus Supplier</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Yakin hapus supplier <strong>{{ $supplier->nama_supp }}</strong>?</p>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-default"
                                                    data-dismiss="modal">Batal</button>
                                                <form action="{{ url('admin/supplier/' . $supplier->id) }}" method="POST">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="modal-qrcode-{{ $supplier->id }}">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Gambar QR Code</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div style="text-align: center;">
                                                    <p style="font-size:20px; font-weight: bold;">
                                                        {{ $supplier->kode_supplier }}</p>
                                                    <div style="display: inline-block;">
                                                        {!! DNS2D::getBarcodeHTML("$supplier->qrcode_supplier", 'QRCODE', 15, 15) !!}
                                                    </div>
                                                    <p style="font-size:20px; font-weight: bold;">
                                                        {{ $supplier->nama_supp }}</p>
                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-default"
                                                        data-dismiss="modal">Batal</button>
                                                    {{-- <form action="{{ url('admin/ban/' . $golongan->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-primary">Cetak</button>
                                                    </form> --}}
                                                    <a href="{{ url('admin/supplier/cetak-pdf/' . $supplier->id) }}"
                                                        class="btn btn-primary btn-sm">
                                                        <i class=""></i> Cetak
                                                    </a>
                                                    {{-- <a href="{{ url('admin/cetak-pdf/' . $golongan->id) }}" target="_blank"
                                                        class="btn btn-outline-primary btn-sm float-end">
                                                        <i class="fa-solid fa-print"></i> Cetak PDV
                                                    </a> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </section>
    <!-- /.card -->
@endsection
