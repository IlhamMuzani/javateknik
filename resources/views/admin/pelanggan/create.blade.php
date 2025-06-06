@extends('layouts.app')

@section('title', 'Tambah Pelanggan')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
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
            {{-- <div class="card"> --}}
            {{-- <div class="card-header">
                    <h3 class="card-title">Tambah Pelanggan</h3>
                </div> --}}
            <!-- /.card-header -->
            <form action="{{ url('admin/pelanggan') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Tambah Pelanggan</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label" for="kategori">Kategori</label>
                            <select class="form-control" id="katPegori" name="kategori">
                                <option value="">- Pilih -</option>
                                <option value="PPN" {{ old('kategori') == 'PPN' ? 'selected' : null }}>
                                    PPN</option>
                                <option value="NON PPN" {{ old('kategori') == 'NON PPN' ? 'selected' : null }}>
                                    NON PPN</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="nama">No. NPWP</label>
                            <input type="text" class="form-control" id="npwp" name="npwp"
                                placeholder="Masukan no npwp" value="{{ old('npwp') }}">
                        </div>
                        <div class="form-group mb-3">
                            <div class="form-group">
                                <label class="form-label" for="golongan">Golongan</label>
                                <select class="form-control" id="golongan_id" name="golongan_id">
                                    <option value="">- Pilih -</option>
                                    @foreach ($golongans as $golongan)
                                        <option value="{{ $golongan->id }}"
                                            {{ old('golongan_id') == $golongan->id ? 'selected' : '' }}>
                                            {{ $golongan->nama_golongan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group  mb-3">
                            <label for="nama_pelanggan">Nama Pelanggan</label>
                            <input type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan"
                                placeholder="Masukan nama pelanggan" value="{{ old('nama_pelanggan') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="nama">Nama Alias</label>
                            <input type="text" class="form-control" id="nama_alias" name="nama_alias"
                                placeholder="Masukan nama alias" value="{{ old('nama_alias') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="alamat">Alamat</label>
                            <textarea type="text" class="form-control" id="alamat" name="alamat" placeholder="Masukan alamat">{{ old('alamat') }}</textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="telp">No. Telepon</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">+62</span>
                                </div>
                                <input type="text" id="telp" name="telp" class="form-control"
                                    placeholder="Masukan nomor telepon" value="{{ old('telp') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="gambar_ktp">Foto KTP</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="gambar_ktp" name="gambar_ktp"
                                    accept="image/*">
                                <label class="custom-file-label" for="gambar_ktp">Masukkan Foto KTP</label>
                            </div>
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
            {{-- </div> --}}
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
