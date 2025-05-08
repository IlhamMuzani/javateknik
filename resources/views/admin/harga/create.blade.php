@extends('layouts.app')

@section('title', 'Tambah Harga')

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
            <form action="{{ url('admin/harga') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Tambah Harga</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nama_harga">Nama Harga</label>
                            <input style="text-transform:uppercase" type="text" class="form-control" id="nama_harga"
                                name="nama_harga" placeholder="Masukkan Nama Harga" value="{{ old('nama_harga') }}">
                        </div>
                    </div>

                </div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Harga</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">

                        <div class="form-group">
                            <label for="harga_a">Harga A</label>
                            <input type="text" class="form-control" id="harga_a" name="harga_a"
                                placeholder="masukkan harga" value="{{ old('harga_a') }}" oninput="formatRupiah(this)"
                                onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                        </div>
                        <div class="form-group">
                            <label for="harga_b">Harga B</label>
                            <input type="text" class="form-control" id="harga_b" name="harga_b"
                                placeholder="masukkan harga" value="{{ old('harga_b') }}" oninput="formatRupiah(this)"
                                onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                        </div>
                        <div class="form-group">
                            <label for="harga_c">Harga C</label>
                            <input type="text" class="form-control" id="harga_c" name="harga_c"
                                placeholder="masukkan harga" value="{{ old('harga_c') }}" oninput="formatRupiah(this)"
                                onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                        </div>
                        <div class="form-group">
                            <label for="harga_d">Harga D</label>
                            <input type="text" class="form-control" id="harga_d" name="harga_d"
                                placeholder="masukkan harga" value="{{ old('harga_d') }}" oninput="formatRupiah(this)"
                                onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                        </div>
                        <div class="form-group">
                            <label for="harga_e">Harga E</label>
                            <input type="text" class="form-control" id="harga_e" name="harga_e"
                                placeholder="masukkan harga" value="{{ old('harga_e') }}" oninput="formatRupiah(this)"
                                onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                        </div>

                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Masukan keterangan">{{ old('keterangan') }}</textarea>
                        </div>
                    </div>

                    <div class="card-footer text-right">
                        <button type="reset" class="btn btn-secondary" id="btnReset">Reset</button>
                        <button type="submit" class="btn btn-primary" id="btnSimpan">Simpan</button>
                        <div id="loading" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i> Sedang Menyimpan...
                        </div>
                    </div>
                </div>
            </form>
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

    <script>
        function formatRupiah(input) {
            // Hapus karakter selain angka
            var value = input.value.replace(/\D/g, "");

            // Format angka dengan menambahkan titik sebagai pemisah ribuan
            value = new Intl.NumberFormat('id-ID').format(value);

            // Tampilkan nilai yang sudah diformat ke dalam input
            input.value = value;
        }
    </script>
@endsection
