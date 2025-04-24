@extends('layouts.app')

@section('title', 'Tambah Type')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/type') }}">Type</a></li>
                        <li class="breadcrumb-item active">Tambah</li>
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
            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5>
                        <i class="icon fas fa-check"></i> Berhasil!
                    </h5>
                    {{ session('success') }}
                </div>
            @endif
            <form action="{{ url('admin/type') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Tambah Type</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group" style="flex: 8;">
                                    <label for="merek_id">Pilih Merek</label>
                                    <select class="select2bs4 select2-hidden-accessible" name="merek_id"
                                        data-placeholder="Cari Merek.." style="width: 100%;" data-select2-id="23"
                                        tabindex="-1" aria-hidden="true" id="merek_id" onchange="getData(0)">
                                        <option value="">- Pilih -</option>
                                        @foreach ($mereks as $merek)
                                            <option value="{{ $merek->id }}"
                                                {{ old('merek_id') == $merek->id ? 'selected' : '' }}>
                                                {{ $merek->nama_merek }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label" for="kode_merek">Kode Merek</label>
                                    <input readonly class="form-control @error('kode_merek') is-invalid @enderror"
                                        id="kode_merek" name="kode_merek" type="text" placeholder=""
                                        value="{{ old('kode_merek') }}" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="nama_type">Nama Type *</label>
                            <input class="form-control @error('nama_type') is-invalid @enderror" id="nama_type"
                                name="nama_type" type="text" placeholder="masukan nama  type"
                                value="{{ old('nama_type') }}" />
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
        function getData(id) {
            var merek_id = document.getElementById('merek_id');
            $.ajax({
                url: "{{ url('admin/type/merek') }}" + "/" + merek_id.value,
                type: "GET",
                dataType: "json",
                success: function(response) {
                    console.log('Respons dari server:', response);

                    var kode_merek = document.getElementById('kode_merek');

                    if (response && response.kode_merek) {
                        kode_merek.value = response.kode_merek;
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Terjadi kesalahan dalam permintaan AJAX:', error);
                }
            });
        }
    </script>
@endsection
