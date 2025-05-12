@extends('layouts.app')

@section('title', 'Harga')

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


    <div class="content-header" style="display: none;" id="mainContent">
        <div class="container-fluid">
            <div class="row mb-2">

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
                    <h3 class="card-title">Harga</h3>
                    <div class="float-right">
                        @if (auth()->user()->menufiturs()->where('nama', 'Data Harga')->wherePivot('can_create', 1)->exists())
                            <a href="{{ url('admin/harga/create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Tambah
                            </a>
                        @endif
                    </div>
                </div>
                <form action="{{ url('admin/harga') }}" method="GET" id="get-keyword" autocomplete="off">
                    @csrf
                    <div class="row align-items-end p-3">
                        <!-- Kategori (Sebelah Kiri) -->
                        <div class="col-md-4">

                        </div>

                        <div class="col-md-4 offset-md-4">
                            <label for="keyword">Cari Harga :</label>
                            <div class="input-group">
                                <input type="search" class="form-control" name="keyword" id="keyword"
                                    value="{{ Request::get('keyword') }}">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="table-responsive" style="overflow-x: auto;">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Kode Harga</th>
                                    <th>Nama Harga</th>
                                    <th>Harga A</th>
                                    <th>Harga B</th>
                                    <th>Harga C</th>
                                    <th>Harga D</th>
                                    <th>Harga E</th>

                                    <th class="text-center" width="120">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($hargas->isEmpty())
                                    <tr>
                                        <td colspan="11" class="text-center">Data tidak ditemukan</td>
                                    </tr>
                                @else
                                    @foreach ($hargas as $harga)
                                        <tr class="{{ $loop->iteration % 2 == 0 ? 'bg-light' : '' }}">
                                            <td class="text-center">
                                                {{ ($hargas->currentPage() - 1) * $hargas->perPage() + $loop->iteration }}
                                            </td>
                                            <td>{{ $harga->kode_harga }}
                                            </td>
                                            <td>{{ $harga->barang->nama_barang ?? null }}
                                            </td>
                                            <td class="text-right">
                                                {{ number_format($harga->harga_a, 0, ',', '.') }}
                                            </td>
                                            <td class="text-right">
                                                {{ number_format($harga->harga_b, 0, ',', '.') }}
                                            </td>
                                            <td class="text-right">
                                                {{ number_format($harga->harga_c, 0, ',', '.') }}
                                            </td>
                                            <td class="text-right">
                                                {{ number_format($harga->harga_d, 0, ',', '.') }}
                                            </td>
                                            <td class="text-right">
                                                {{ number_format($harga->harga_e, 0, ',', '.') }}
                                            </td>
                                            <td class="text-center">
                                                @if (auth()->user()->menufiturs()->where('nama', 'Data Harga')->wherePivot('can_update', 1)->exists())
                                                    <button type="button" class="btn btn-warning btn-sm"
                                                        data-toggle="modal" data-target="#modal-edit-{{ $harga->id }}">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                @endif
                                                @if (auth()->user()->menufiturs()->where('nama', 'Data Harga')->wherePivot('can_delete', 1)->exists())
                                                    <button type="submit" class="btn btn-danger btn-sm" data-toggle="modal"
                                                        data-target="#modal-hapus-{{ $harga->id }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                        <div class="modal fade" id="modal-hapus-{{ $harga->id }}">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Hapus Harga</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Yakin hapus harga
                                                            <strong>{{ $harga->nama_harga }}</strong>?
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer justify-content-between">
                                                        <button type="button" class="btn btn-default"
                                                            data-dismiss="modal">Batal</button>
                                                        <form action="{{ url('admin/harga/' . $harga->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('delete')
                                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @include('admin.harga.update', [
                                            'harga' => $harga,
                                        ])
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    {{ $hargas->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </section>

    <!-- /.card -->
@endsection
