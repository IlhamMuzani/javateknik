@extends('layouts.app')

@section('title', 'Tambah Barang')

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
            <form action="{{ url('admin/barang/' . $barang->id) }}" method="POST" enctype="multipart/form-data"
                autocomplete="off">
                @csrf
                @method('put')
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Klasifikasi</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4">
                                <div hidden class="form-group mt-2">
                                    <label for="merek_id">Merek Id</label>
                                    <input readonly type="text" class="form-control" id="merek_id" name="merek_id"
                                        placeholder="" value="{{ old('merek_id', $barang->merek_id) }}">
                                </div>
                                <div class="form-group">
                                    <label for="kode_merek">Kode Merek</label>
                                    <div class="input-group">
                                        <input style="margin-right: 10px;" readonly type="text" class="form-control"
                                            id="kode_merek" name="kode_merek" placeholder=""
                                            value="{{ old('kode_merek', $barang->merek->kode_merek ?? null) }}"
                                            onclick="showCategoryModalMerek(this.value)">
                                        <button type="button" class="btn btn-outline-primary"
                                            onclick="showCategoryModalMerek(this.value)">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="form-group mt-2">
                                    <label for="nama_merek">Nama Merek</label>
                                    <input readonly type="text" class="form-control" id="nama_merek" name="nama_merek"
                                        placeholder="" value="{{ old('nama_merek', $barang->merek->nama_merek ?? null) }}"
                                        onclick="showCategoryModalMerek(this.value)">
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div hidden class="form-group mt-2">
                                    <label for="type_id">Type Id</label>
                                    <input readonly type="text" class="form-control" id="type_id" name="type_id"
                                        placeholder="" value="{{ old('type_id', $barang->type_id) }}">
                                </div>
                                <div class="form-group">
                                    <label for="kode_type">Kode Type</label>
                                    <div class="input-group">
                                        <input style="margin-right: 10px;" readonly type="text" class="form-control"
                                            id="kode_type" name="kode_type" placeholder=""
                                            value="{{ old('kode_type', $barang->type->kode_type ?? null) }}"
                                            onclick="showCategoryModalType(this.value)">
                                        <button type="button" class="btn btn-outline-primary"
                                            onclick="showCategoryModalType(this.value)">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="nama_type">Nama Type</label>
                                    <input readonly type="text" class="form-control" id="nama_type" name="nama_type"
                                        placeholder="" value="{{ old('nama_type', $barang->type->nama_type ?? null) }}"
                                        onclick="showCategoryModalType(this.value)">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div hidden class="form-group mt-2">
                                    <label for="bagian_id">Bagian Id</label>
                                    <input readonly type="text" class="form-control" id="bagian_id" name="bagian_id"
                                        placeholder="" value="{{ old('bagian_id', $barang->bagian_id) }}">
                                </div>
                                <div class="form-group">
                                    <label for="kode_bagian">Kode Bagian</label>
                                    <div class="input-group">
                                        <input style="margin-right: 10px;" readonly type="text" class="form-control"
                                            id="kode_bagian" name="kode_bagian" placeholder=""
                                            value="{{ old('kode_bagian', $barang->bagian->kode_bagian ?? null) }}"
                                            onclick="showCategoryModalBagian(this.value)">
                                        <button type="button" class="btn btn-outline-primary"
                                            onclick="showCategoryModalBagian(this.value)">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="nama_bagian">Nama Bagian</label>
                                    <input readonly type="text" class="form-control" id="nama_bagian"
                                        name="nama_bagian" placeholder=""
                                        value="{{ old('nama_bagian', $barang->bagian->nama_bagian ?? null) }}"
                                        onclick="showCategoryModalBagian(this.value)">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Barang</h3>
                    </div>
                    <div class="card-body">
                        <div hidden class="form-group">
                            <label for="kode_last">Kode Last DB</label>
                            <input readonly type="text" class="form-control" id="kode_last" name="kode_last"
                                placeholder="" value="{{ old('kode_last', $barang->kode_last) }}">
                        </div>
                        <div class="form-group">
                            <label for="kode_barang">Kode Barang</label>
                            <input readonly type="text" class="form-control" id="kode_barang" name="kode_barang"
                                placeholder="" value="{{ old('kode_barang', $barang->kode_barang) }}">
                        </div>
                        <div class="form-group">
                            <label for="nama">Nama Barang</label>
                            <input type="text" class="form-control" id="nama_barang" name="nama_barang"
                                placeholder="Masukan nama barang" value="{{ old('nama_barang', $barang->nama_barang) }}">
                        </div>
                        <div class="form-group">
                            <label for="satuan_id">Satuan</label>
                            <select class="form-control" id="satuan_id" name="satuan_id">
                                <option value="">-- Pilih Satuan --</option>
                                @foreach ($satuans as $satuan)
                                    <option value="{{ $satuan->id }}"
                                        {{ old('satuan_id', $barang->satuan_id) == $satuan->id ? 'selected' : '' }}>
                                        {{ $satuan->nama_satuan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="harga_barang">Harga</label>
                            <input type="text" class="form-control" id="harga_barang" name="harga_barang"
                                placeholder="Masukan harga_barang"
                                value="{{ old('harga_barang', $barang->harga_barang) }}">
                        </div>

                        <script>
                            const hargaInput = document.getElementById('harga_barang');
                            hargaInput.addEventListener('input', function(e) {
                                let value = this.value.replace(/[^\d]/g, '');
                                this.value = new Intl.NumberFormat('id-ID').format(value);
                            });
                        </script>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3" placeholder="Masukan keterangan">{{ old('keterangan', $barang->keterangan) }}</textarea>
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

        <div class="modal fade" id="tableMerek" data-backdrop="static">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Data Merek</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table id="datatables66" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Kode Merek</th>
                                    <th>Nama Merek</th>
                                    <th style="text-align:center">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mereks as $merek)
                                    <tr
                                        onclick="getSelectedDataMerek('{{ $merek->id }}','{{ $merek->kode_merek ?? null }}', '{{ $merek->nama_merek ?? null }}')">
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $merek->kode_merek ?? null }}</td>
                                        <td>{{ $merek->nama_merek ?? null }}</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-primary btn-sm"
                                                onclick="getSelectedDataMerek('{{ $merek->id }}','{{ $merek->kode_merek ?? null }}', '{{ $merek->nama_merek ?? null }}')">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="tableType" data-backdrop="static">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Data Type</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Kode Type</th>
                                    <th>Nama Type</th>
                                    <th style="text-align:center">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data akan dimuat melalui AJAX -->
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="tableBagian" data-backdrop="static">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Data Bagian</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table id="example2" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Kode Bagian</th>
                                    <th>Nama Bagian</th>
                                    <th style="text-align:center">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bagians as $item)
                                    <tr
                                        onclick="getSelectedDataBagian('{{ $item->id }}','{{ $item->kode_bagian }}', '{{ $item->nama_bagian }}')">
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $item->kode_bagian ?? null }}</td>
                                        <td>{{ $item->nama_bagian }}</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-primary btn-sm"
                                                onclick="getSelectedDataBagian('{{ $item->id }}','{{ $item->kode_bagian }}', '{{ $item->nama_bagian }}')">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(function() {
            $("#example2").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            bsCustomFileInput.init();
            $('.select2').select2()
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })
        });
    </script>
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
        function showCategoryModalMerek(selectedCategory) {
            $('#tableMerek').modal('show');
        }

        function getSelectedDataMerek(MerekId, KodeMerek, NamaMerek) {
            // Set the values in the form fields
            document.getElementById('merek_id').value = MerekId;
            document.getElementById('kode_merek').value = KodeMerek;
            document.getElementById('nama_merek').value = NamaMerek;
            // Close the modal (if needed)
            $('#tableMerek').modal('hide');
            updateKodeTypeDanBagian();
            updateKodeBarang();
        }
    </script>

    <script>
        function getSelectedDataType(TypeId, KodeType, NamaType) {
            document.getElementById('type_id').value = TypeId;
            document.getElementById('kode_type').value = KodeType;
            document.getElementById('nama_type').value = NamaType;

            $('#tableType').modal('hide');
            updateKodeBarang();
        }
    </script>

    <script>
        function showCategoryModalType() {
            const merekId = document.getElementById('merek_id').value;
            if (!merekId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Silakan pilih merek terlebih dahulu!',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
                return;
            }


            // Ambil data type sesuai merek_id
            $.ajax({
                url: `get-types-by-merek/${merekId}`,
                type: 'GET',
                success: function(response) {
                    const tbody = $('#tableType tbody');
                    tbody.empty();

                    if (response.length === 0) {
                        tbody.append('<tr><td colspan="4" class="text-center">Data tidak ditemukan</td></tr>');
                    }

                    response.forEach((type, index) => {
                        const row = `
                    <tr onclick="getSelectedDataType('${type.id}','${type.kode_type}','${type.nama_type}')">
                        <td class="text-center">${index + 1}</td>
                        <td>${type.kode_type}</td>
                        <td>${type.nama_type}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-primary btn-sm"
                                onclick="getSelectedDataType('${type.id}','${type.kode_type}','${type.nama_type}')">
                                <i class="fas fa-plus"></i>
                            </button>
                        </td>
                    </tr>`;
                        tbody.append(row);
                    });

                    $('#tableType').modal('show');
                },
                error: function() {
                    alert('Gagal memuat data Type.');
                }
            });
        }
    </script>

    <script>
        function getSelectedDataBagian(BagianId, KodeBagian, NamaBagian) {
            document.getElementById('bagian_id').value = BagianId;
            document.getElementById('kode_bagian').value = KodeBagian;
            document.getElementById('nama_bagian').value = NamaBagian;

            $('#tableBagian').modal('hide');
            updateKodeBarang();
        }
    </script>

    <script>
        function showCategoryModalBagian(selectedCategory) {
            $('#tableBagian').modal('show');
        }

        function getSelectedDataBagian(BagianId, KodeBagian, NamaBagian) {
            document.getElementById('bagian_id').value = BagianId;
            document.getElementById('kode_bagian').value = KodeBagian;
            document.getElementById('nama_bagian').value = NamaBagian;

            $('#tableBagian').modal('hide');
            updateKodeBarang();
        }
    </script>

    {{-- kode barang  --}}
    <script>
        function numberToAlphabet(n) {
            const alphabets = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            if (n < 1 || n > 26) return "?";
            return alphabets[n - 1];
        }

        function updateKodeBarang() {
            const kodeMerek = document.getElementById('kode_merek').value;
            const kodeType = document.getElementById('kode_type').value;
            const kodeBagian = document.getElementById('kode_bagian').value;
            const kodeLastDb = document.getElementById('kode_last').value; // Ambil angka dari kode_last

            if (kodeMerek && kodeType && kodeBagian && kodeLastDb) {
                // Ambil angka dari kode
                const angkaMerek = parseInt(kodeMerek.match(/\d+/)[0], 10);
                const angkaType = parseInt(kodeType.match(/\d+/)[0], 10);
                const angkaBagian = parseInt(kodeBagian.match(/\d+/)[0], 10);

                // Konversi angka ke huruf
                const hurufMerek = numberToAlphabet(angkaMerek);
                const hurufType = numberToAlphabet(angkaType);
                const hurufBagian = numberToAlphabet(angkaBagian);

                // Ambil angka dari kode_last tanpa dijumlahkan
                const angkaBarang = kodeLastDb; // Ambil nilai yang sudah ada tanpa perubahan
                const kodeBarang = hurufMerek + hurufType + hurufBagian + angkaBarang;

                // Set nilai ke input kode_barang
                document.getElementById('kode_barang').value = kodeBarang;
            }
        }
    </script>
@endsection
