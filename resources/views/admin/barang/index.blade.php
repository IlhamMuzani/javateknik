@extends('layouts.app')

@section('title', 'Data Barang')

@section('content')
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
                    <h1 class="m-0">Data Barang</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Data Barang</li>
                    </ol>
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
                    <h3 class="card-title">Data barang</h3>
                    <div class="float-right">
                        @if (auth()->user()->menufiturs()->where('nama', 'Data Barang')->wherePivot('can_create', 1)->exists())
                            <a href="{{ url('admin/barang/create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Tambah
                            </a>
                        @endif
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form method="GET" id="form-action">
                        <div class="row p-3">
                            <div class="col-0 col-md-8"></div>
                            <div class="col-md-4">
                                <label for="keyword">Cari Barang :</label>
                                <div class="input-group">
                                    <input type="search" class="form-control" name="keyword" id="keyword"
                                        value="{{ Request::get('keyword') }}"
                                        onsubmit="event.preventDefault();
                                        document.getElementById('get-keyword').submit();">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <table class="table table-bordered table-striped table-hover" style="font-size: 13px">
                        <thead class="thead-dark">
                            <tr>
                                <th class="text-center">No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Merek</th>
                                <th>Type</th>
                                <th>Bagian</th>
                                <th>Stok</th>
                                <th class="text-center">Qr Code</th>
                                <th class="text-center" width="140">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($barangs as $barang)
                                <tr>
                                    <td class="text-center">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>{{ $barang->kode_barang }}</td>
                                    <td>{{ $barang->nama_barang }}</td>
                                    <td>{{ $barang->merek->nama_merek ?? null }}</td>
                                    <td>{{ $barang->type->nama_type ?? null }}</td>
                                    <td>{{ $barang->bagian->nama_bagian ?? null }}</td>
                                    <td>{{ $barang->jumlah }}</td>
                                    {{-- <td>{{ $barang->harga }}</td> --}}
                                    <td data-toggle="modal" data-target="#modal-qrcode-{{ $barang->id }}"
                                        style="text-align: center;">
                                        <div style="display: inline-block;">
                                            {!! DNS2D::getBarcodeHTML("$barang->qrcode_barang", 'QRCODE', 1, 1) !!}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if (auth()->user()->menufiturs()->where('nama', 'Data Barang')->wherePivot('can_show', 1)->exists())
                                            <button type="submit" class="btn btn-danger btn-sm" data-toggle="modal"
                                                data-target="#modal-hapus-{{ $barang->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                        @if (auth()->user()->menufiturs()->where('nama', 'Data Barang')->wherePivot('can_update', 1)->exists())
                                            <a href="{{ url('admin/barang/' . $barang->id . '/edit') }}"
                                                class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <div class="modal fade" id="modal-hapus-{{ $barang->id }}">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Hapus barang</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Yakin hapus barang <strong>{{ $barang->nama }}</strong>?</p>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-default"
                                                    data-dismiss="modal">Batal</button>
                                                <form action="{{ url('admin/barang/' . $barang->id) }}" method="POST">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="modal-qrcode-{{ $barang->id }}">
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
                                                    <div style="display: inline-block;">
                                                        {!! DNS2D::getBarcodeHTML("$barang->qrcode_barang", 'QRCODE', 15, 15) !!}
                                                    </div>
                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-default"
                                                        data-dismiss="modal">Batal</button>
                                                    <a href="{{ url('admin/barang/cetak-pdf/' . $barang->id) }}"
                                                        class="btn btn-primary btn-sm">
                                                        <i class=""></i> Cetak
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <script>
        $(document).ready(function() {
            // Detect the change event on the 'status' dropdown
            $('#kategori').on('change', function() {
                // Get the selected value
                var selectedValue = $(this).val();

                // Check the selected value and redirect accordingly
                switch (selectedValue) {
                    case 'barang':
                        window.location.href = "{{ url('admin/barang') }}";
                        break;
                    case 'barangnon':
                        window.location.href = "{{ url('admin/barangnonbesi') }}";
                        break;
                    default:
                        // Handle other cases or do nothing
                        break;
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            var toggleAll = $("#toggle-all");
            var isExpanded = false; // Status untuk melacak apakah semua detail telah dibuka

            toggleAll.click(function() {
                if (isExpanded) {
                    $(".collapse").collapse("hide");
                    toggleAll.text("All Toggle Detail");
                    isExpanded = false;
                } else {
                    $(".collapse").collapse("show");
                    toggleAll.text("All Close Detail");
                    isExpanded = true;
                }
            });

            // Event listener untuk mengubah status jika ada interaksi manual
            $(".accordion-toggle").click(function() {
                var target = $(this).data("target");
                if ($("#" + target).hasClass("show")) {
                    $("#" + target).collapse("hide");
                } else {
                    $("#" + target).collapse("show");
                }
            });
        });
    </script>
    <!-- /.card -->
@endsection
