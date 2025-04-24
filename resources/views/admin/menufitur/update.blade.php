@extends('layouts.app')

@section('title', 'Perbarui Menu Fitur')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/menu-fitur') }}">Menu Fitur</a></li>
                        <li class="breadcrumb-item active">Perbarui</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="container-fluid">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5>
                        <i class="icon fas fa-ban"></i> Gagal!
                    </h5>
                    @foreach (session('error') as $error)
                        - {{ $error }} <br>
                    @endforeach
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Perbarui Menu Fitur</h3>
                </div>
                <!-- /.card-header -->
                <form action="{{ url('admin/menu-fitur/' . $menufitur->id) }}" method="POST" enctype="multipart/form-data"
                    autocomplete="off">
                    @csrf
                    @method('put')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="kategori">Kategori</label>
                            <select class="form-control" id="kategori" name="kategori">
                                <option value="">- Pilih -</option>
                                <option value="MASTER"
                                    {{ old('kategori', $menufitur->kategori) == 'MASTER' ? 'selected' : null }}>
                                    MASTER</option>
                                <option value="OPERASIONAL"
                                    {{ old('kategori', $menufitur->kategori) == 'OPERASIONAL' ? 'selected' : null }}>
                                    OPERASIONAL</option>
                                <option value="PEMELIHARAAN_KENDARAAN"
                                    {{ old('kategori', $menufitur->kategori) == 'PEMELIHARAAN_KENDARAAN' ? 'selected' : null }}>
                                    PEMELIHARAAN KENDARAAN</option>
                                <option value="TRANSAKSI"
                                    {{ old('kategori', $menufitur->kategori) == 'TRANSAKSI' ? 'selected' : null }}>
                                    TRANSAKSI</option>
                                <option value="FINANCE"
                                    {{ old('kategori', $menufitur->kategori) == 'FINANCE' ? 'selected' : null }}>
                                    FINANCE</option>
                                <option value="LAPORAN"
                                    {{ old('kategori', $menufitur->kategori) == 'LAPORAN' ? 'selected' : null }}>
                                    LAPORAN</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama"
                                placeholder="Masukan nama" value="{{ old('nama', $menufitur->nama) }}">
                        </div>
                        <div class="form-group">
                            <label for="route">Route</label>
                            <input type="text" class="form-control" id="route" name="route"
                                placeholder="Masukan route" value="{{ old('route', $menufitur->route) }}">
                        </div>
                    </div>
            </div>
            <div class="card-footer text-right">
                <button type="reset" class="btn btn-secondary">Reset</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
            </form>
        </div>
        </div>
    </section>
@endsection
