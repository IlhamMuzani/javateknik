@extends('layouts.app')

@section('title', 'Tambah Menu Fitur')

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
                </div><!-- /.col -->
                <div class="col-sm-6">
                   
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content" style="display: none;" id="mainContentSection">
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
                    <h3 class="card-title">Tambah Menu Fitur</h3>
                </div>
                <!-- /.card-header -->
                <form action="{{ url('admin/menu-fitur') }}" method="POST" enctype="multipart/form-data"
                    autocomplete="off">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="kategori">Kategori</label>
                            <select class="form-control" id="kategori" name="kategori">
                                <option value="">- Pilih -</option>
                                <option value="MASTER" {{ old('kategori') == 'MASTER' ? 'selected' : null }}>
                                    MASTER</option>
                                <option value="OPERASIONAL" {{ old('kategori') == 'OPERASIONAL' ? 'selected' : null }}>
                                    OPERASIONAL</option>
                                <option value="PEMELIHARAAN_KENDARAAN"
                                    {{ old('kategori') == 'PEMELIHARAAN_KENDARAAN' ? 'selected' : null }}>
                                    PEMELIHARAAN KENDARAAN</option>
                                <option value="TRANSAKSI" {{ old('kategori') == 'TRANSAKSI' ? 'selected' : null }}>
                                    TRANSAKSI</option>
                                <option value="FINANCE" {{ old('kategori') == 'FINANCE' ? 'selected' : null }}>
                                    FINANCE</option>
                                <option value="LAPORAN" {{ old('kategori') == 'LAPORAN' ? 'selected' : null }}>
                                    LAPORAN</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama"
                                placeholder="Masukan nama" value="{{ old('nama') }}">
                        </div>
                        <div class="form-group">
                            <label for="route">Route</label>
                            <input type="text" class="form-control" id="route" name="route"
                                placeholder="Masukan route" value="{{ old('route') }}">
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="reset" class="btn btn-secondary" id="btnReset">Reset</button>
                        <button type="submit" class="btn btn-primary" id="btnSimpan">Simpan</button>
                        <div id="loading" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i> Sedang Menyimpan...
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script>
        $(document).ready(function() {
            // Tambahkan event listener pada tombol "Simpan"
            $('#btnSimpan').click(function() {
                // Sembunyikan tombol "Simpan" dan "Reset", serta tampilkan elemen loading
                $(this).hide();
                $('#btnReset').hide(); // Tambahkan id "btnReset" pada tombol "Reset"
                $('#loading').show();

                // Lakukan pengiriman formulir
                $('form').submit();
            });
        });
    </script>
@endsection
