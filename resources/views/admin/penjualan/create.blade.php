@extends('layouts.app')

@section('title', 'Penjualan')

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
            {{-- <div class="row mb-2">
                <div class="col-sm-6">
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/pembelian_part') }}">Transaksi</a></li>
                        <li class="breadcrumb-item active">Purchase Order Pembelian</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row --> --}}
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content" style="display: none;" id="mainContentSection">
        <div class="container-fluid">
            @if (session('error_pelanggans') || session('error_pesanans'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5>
                        <i class="icon fas fa-ban"></i> Gagal!
                    </h5>
                    @if (session('error_pelanggans'))
                        @foreach (session('error_pelanggans') as $error)
                            - {{ $error }} <br>
                        @endforeach
                    @endif
                    @if (session('error_pesanans'))
                        @foreach (session('error_pesanans') as $error)
                            - {{ $error }} <br>
                        @endforeach
                    @endif
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
            <form action="{{ url('admin/penjualan') }}" method="post" autocomplete="off">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Detail Pelanggan</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">

                        <div class="form-group">
                            <label class="form-label" for="kategori">Kategori</label>
                            <select class="form-control" id="kategori" name="kategori">
                                <option value="">- Pilih -</option>
                                <option value="PPN" {{ old('kategori') == 'PPN' ? 'selected' : null }}>
                                    PPN</option>
                                <option value="NON PPN" {{ old('kategori') == 'NON PPN' ? 'selected' : null }}>
                                    NON PPN</option>
                            </select>
                            <div id="error-kategori" class="text-danger mt-1" style="font-size: 0.9em;"></div>
                        </div>
                        <!-- Row Pertama -->
                        <div class="row">
                            <!-- ID Pelanggan (Hidden) -->
                            <div class="col-md-4" hidden>
                                <label for="pelanggan_id">ID Pelanggan</label>
                                <input type="text" class="form-control" id="pelanggan_id" name="pelanggan_id"
                                    value="{{ old('pelanggan_id') }}">
                            </div>

                            <!-- Kode Pelanggan dan Button -->
                            <div class="col-md-6">
                                <label class="form-label" for="kode_pelanggan">Kode Pelanggan *</label>
                                <div class="input-group mb-3">
                                    <input onclick="showCategoryModalpelanggan(this.value)"
                                        class="form-control @error('kode_pelanggan') is-invalid @enderror"
                                        id="kode_pelanggan" name="kode_pelanggan" type="text"
                                        value="{{ old('kode_pelanggan') }}" readonly />
                                    <button onclick="showCategoryModalpelanggan(this.value)" style="margin-left: 10px"
                                        type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                        data-target="#modal-pelanggan">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                                <div id="error-pelanggan" class="text-danger mt-1" style="font-size: 0.9em;">
                                </div> <!-- Pesan error -->
                            </div>

                            <!-- Nama Pelanggan -->
                            <div class="col-md-6">
                                <label for="nama_pelanggan">Nama Pelanggan</label>
                                <input onclick="showCategoryModalpelanggan(this.value)" type="text" class="form-control"
                                    id="nama_pelanggan" name="nama_pelanggan" value="{{ old('nama_pelanggan') }}" readonly>
                            </div>

                            <!-- Golongan Pelanggan -->
                            <div class="col-md-6">
                                <label for="golongan">Golongan Pelanggan</label>
                                <input onclick="showCategoryModalpelanggan(this.value)" type="text" class="form-control"
                                    id="golongan" name="golongan" value="{{ old('golongan') }}" readonly>
                            </div>

                            <!-- No. Telepon -->
                            <div class="col-md-6">
                                <label for="telp">No. Telepon</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">+62</span>
                                    </div>
                                    <input onclick="showCategoryModalpelanggan(this.value)" type="text" id="telp"
                                        name="telp" class="form-control" value="{{ old('telp') }}" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Row Kedua: Alamat -->
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label for="alamat">Alamat</label>
                                <textarea onclick="showCategoryModalpelanggan(this.value)" class="form-control" id="alamat" name="alamat"
                                    rows="2" readonly>{{ old('alamat') }}</textarea>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Tambah Barang</h3>
                        <div class="float-right">
                            <button type="button" class="btn btn-primary btn-sm" id="addPesananBtn"
                                onclick="addPesanan()">
                                <i class="fas fa-plus">Tambah</i>
                            </button>
                            <input type="text" id="scanInput" placeholder="Scan Barcode" oninput="handleScan()">
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr style="font-size:14px">
                                    <th class="text-center">No</th>
                                    <th hidden>Qrcode Barang</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Harga</th>
                                    <th>Qty</th>
                                    <th>Diskon</th>
                                    <th>Satuan</th>
                                    <th>Total</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody id="tabel-pembelian">
                                {{-- <tr id="pembelian-0">
                                    <td style="width: 70px; font-size:14px" class="text-center" id="urutan">1</td>
                                    <td hidden>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="barang_id-0"
                                                name="barang_id[]">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input style="font-size:14px" type="text" class="form-control qrcode_barang"
                                                id="qrcode_barang-0" readonly onclick="barangModal(0)" name="qrcode_barang[]">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input style="font-size:14px" type="text" class="form-control kode_barang"
                                                id="kode_barang-0" readonly onclick="barangModal(0)" name="kode_barang[]">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input style="font-size:14px" type="text" class="form-control"
                                                id="nama_barang-0" readonly onclick="barangModal(0)" name="nama_barang[]">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input style="text-align: right" type="text" onclick="barangModal(0)" readonly
                                                class="form-control harga" id="harga-0" name="harga[]"
                                                data-row-id="0">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input style="font-size:14px" type="number" class="form-control jumlah"
                                                id="jumlah-0" name="jumlah[]" data-row-id="0">
                                        </div>
                                    </td>
                                     <td>
                                        <div class="form-group">
                                            <input style="font-size:14px" type="number" class="form-control diskon"
                                                id="diskon-0" name="diskon[]" data-row-id="0">
                                        </div>
                                    </td>
                                    <td style="width: 220px">
                                        <div style="font-size:14px" class="form-group">
                                            <select class="select2bs4 select21-hidden-accessible" name="satuan_id[]"
                                                data-placeholder="Pilih Satuan.." style="width: 100%;"
                                                data-select21-id="23" tabindex="-1" aria-hidden="true"
                                                id="satuan_id-0">
                                                <option value="">- Pilih -</option>
                                                @foreach ($satuans as $satuan)
                                                    <option value="{{ $satuan->id }}">{{ $satuan->kode_satuan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="form-group">
                                            <input style="text-align: right" onclick="barangModal(0)" type="text"
                                                class="form-control total" id="total-0" name="total[]" readonly>
                                        </div>
                                    </td>
                                    <td style="width: 100px">
                                        <button type="button" class="btn btn-primary btn-sm" onclick="barangModal(0)">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        <button style="margin-left:5px" type="button" class="btn btn-danger btn-sm"
                                            onclick="removeBan(0)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr> --}}
                            </tbody>
                        </table>
                    </div>
                    <div class="card-body">
                        <!-- Tambahkan setelah </table> -->
                        <div class="row mt-4">
                            <!-- Total Harga -->
                            <div class="col-md-4 offset-md-8">
                                <div class="form-group">
                                    <label for="total_harga">Sub Total</label>
                                    <input style="text-align: right" type="text" class="form-control"
                                        id="total_harga" name="total_harga" readonly>
                                </div>
                            </div>
                            {{-- ppn  --}}
                            <div class="col-md-4 offset-md-8">
                                <div class="form-group">
                                    <label for="ppn">PPN 11%</label>
                                    <input style="text-align: right" type="text" class="form-control" id="ppn"
                                        name="ppn" readonly>
                                </div>
                            </div>
                            <div class="col-md-4 offset-md-8">
                                <hr
                                    style="height: 2px; background-color: #000000; border: none; margin-top: 10px; margin-bottom: 10px;">
                            </div>

                            <!-- Grand Total -->
                            <div class="col-md-4 offset-md-8">
                                <div class="form-group">
                                    <label for="grand_total">Grand Total</label>
                                    <input style="text-align: right" type="text" class="form-control"
                                        id="grand_total" name="grand_total" readonly>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card">
                        <div id="tableBarang" style="display: none;">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                {{-- <h4 class="card-title">Data Barang</h4> --}}
                                                <button type="button" class="close" onclick="closeTableBarang()">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="card-body">
                                                <div class="m-2">
                                                    <input type="text" id="searchInput" class="form-control"
                                                        placeholder="Search...">
                                                </div>
                                                <table id="tables" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">No</th>
                                                            <th hidden>QrCode Barang</th>
                                                            <th>Kode Barang</th>
                                                            <th>Nama barang</th>
                                                            {{-- <th>Harga</th> --}}
                                                            <th>Merek</th>
                                                            <th>Opsi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($barangs as $barang)
                                                            <tr data-barang_id="{{ $barang->id }}"
                                                                data-qrcode_barang="{{ $barang->qrcode_barang }}"
                                                                data-kode_barang="{{ $barang->kode_barang }}"
                                                                data-nama_barang="{{ $barang->nama_barang }}"
                                                                data-harga_a="{{ $barang->harga->first()->harga_a ?? 0 }}"
                                                                data-harga_b="{{ $barang->harga->first()->harga_b ?? 0 }}"
                                                                data-harga_c="{{ $barang->harga->first()->harga_c ?? 0 }}"
                                                                data-harga_d="{{ $barang->harga->first()->harga_d ?? 0 }}"
                                                                data-harga_e="{{ $barang->harga->first()->harga_e ?? 0 }}"
                                                                data-param="{{ $loop->index }}"
                                                                onclick="getBarang({{ $loop->index }})">
                                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                                <td hidden>{{ $barang->qrcode_barang }}</td>
                                                                <td>{{ $barang->kode_barang }}</td>
                                                                <td>{{ $barang->nama_barang }}</td>
                                                                <td>{{ $barang->merek->nama_merek ?? null }}</td>
                                                                {{-- <td> {{ number_format($barang->harga, 0, ',', '.') }}</td> --}}
                                                                <td class="text-center">
                                                                    <button type="button" class="btn btn-primary btn-sm"
                                                                        onclick="getBarang({{ $loop->index }})">
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
                            </div>
                        </div>

                    </div>
                    <div class="card-footer text-right mt-3">
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

    <div class="modal fade" id="tableBarangModal" data-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Data Barang</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="m-2">
                        <input type="text" id="searchInputModal" class="form-control" placeholder="Search...">
                    </div>
                    <table id="tablesModal" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th hidden>Qrcode Barang</th>
                                <th>Kode Barang</th>
                                <th>Nama barang</th>
                                <th>Merek</th>
                                <th>Harga</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($barangs as $barang)
                                <tr data-barang_id="{{ $barang->id }}"
                                    data-qrcode_barang="{{ $barang->qrcode_barang }}"
                                    data-kode_barang="{{ $barang->kode_barang }}"
                                    data-nama_barang="{{ $barang->nama_barang }}"
                                    data-harga_a="{{ $barang->harga->first()->harga_a ?? 0 }}"
                                    data-harga_b="{{ $barang->harga->first()->harga_b ?? 0 }}"
                                    data-harga_c="{{ $barang->harga->first()->harga_c ?? 0 }}"
                                    data-harga_d="{{ $barang->harga->first()->harga_d ?? 0 }}"
                                    data-harga_e="{{ $barang->harga->first()->harga_e ?? 0 }}"
                                    data-param="{{ $loop->index }}" onclick="getBarangModal({{ $loop->index }})">
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td hidden>{{ $barang->qrcode_barang }}</td>
                                    <td>{{ $barang->kode_barang }}</td>
                                    <td>{{ $barang->nama_barang }}</td>
                                    <td>{{ $barang->merek->nama_merek ?? null }}</td>
                                    <td class="harga_a">
                                        {{ number_format($barang->harga->first()->harga_a ?? 0, 0, ',', '.') }}</td>
                                    <td class="harga_b">
                                        {{ number_format($barang->harga->first()->harga_b ?? 0, 0, ',', '.') }}</td>
                                    <td class="harga_c">
                                        {{ number_format($barang->harga->first()->harga_c ?? 0, 0, ',', '.') }}</td>
                                    <td class="harga_d">
                                        {{ number_format($barang->harga->first()->harga_d ?? 0, 0, ',', '.') }}</td>
                                    <td class="harga_e">
                                        {{ number_format($barang->harga->first()->harga_e ?? 0, 0, ',', '.') }}</td>
                                    <td>
                                        <button type="button" id="btnTambah" class="btn btn-primary btn-sm"
                                            onclick="getBarangModal({{ $loop->index }})">
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

    <div class="modal fade" id="tablePelanggan" data-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Data Pelanggan</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive scrollbar m-2">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead class="bg-200 text-900">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Kode Pelanggan</th>
                                    <th>Nama Pelanggan</th>
                                    <th>Alamat</th>
                                    <th>Telepon</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pelanggans as $pelanggan)
                                    <tr
                                        onclick="getSelectedDatapelanggan('{{ $pelanggan->id }}', '{{ $pelanggan->kode_pelanggan }}', '{{ $pelanggan->nama_pelanggan }}', '{{ $pelanggan->golongan->nama_golongan ?? null }}', '{{ $pelanggan->telp }}', '{{ $pelanggan->alamat }}')">
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $pelanggan->kode_pelanggan }}</td>
                                        <td>{{ $pelanggan->nama_pelanggan }}</td>
                                        <td>{{ $pelanggan->alamat }}</td>
                                        <td>{{ $pelanggan->telp }}</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-primary btn-sm"
                                                onclick="getSelectedDatapelanggan('{{ $pelanggan->id }}', '{{ $pelanggan->kode_pelanggan }}', '{{ $pelanggan->nama_pelanggan }}', '{{ $pelanggan->golongan->nama_golongan ?? null }}', '{{ $pelanggan->telp }}', '{{ $pelanggan->alamat }}')">
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
    </div>

    {{-- swetaalert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- manual input barang  --}}
    <script>
        var activeSpecificationIndexModal = 0;

        function barangModal(param) {
            var pelangganId = $('#pelanggan_id').val();

            if (!pelangganId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pilih Pelanggan',
                    text: 'Silakan pilih pelanggan terlebih dahulu sebelum memilih barang.',
                });
                return; // hentikan fungsi jika pelanggan belum dipilih
            }

            activeSpecificationIndexModal = param;
            $('#tableBarangModal').modal('show');
        }


        function getBarangModal(rowIndex) {
            var selectedRow = $('#tablesModal tbody tr:eq(' + rowIndex + ')');
            var golongan = $('#golongan').val(); // Ambil golongan dari input

            // Ambil data umum
            var barang_id = selectedRow.data('barang_id');
            var qrcode_barang = selectedRow.data('qrcode_barang');
            var kode_barang = selectedRow.data('kode_barang');
            var nama_barang = selectedRow.data('nama_barang');

            // Ambil harga sesuai golongan
            var harga = 0;
            switch (golongan) {
                case 'A':
                    harga = selectedRow.data('harga_a');
                    break;
                case 'B':
                    harga = selectedRow.data('harga_b');
                    break;
                case 'C':
                    harga = selectedRow.data('harga_c');
                    break;
                case 'D':
                    harga = selectedRow.data('harga_d');
                    break;
                case 'E':
                    harga = selectedRow.data('harga_e');
                    break;
                default:
                    harga = 0;
            }

            function formatRupiahModal(angka) {
                if (!angka) return '0';
                angka = parseFloat(angka.toString().replace(/\D/g, '')) || 0;
                return new Intl.NumberFormat('id-ID').format(angka);
            }

            // Update form input
            $('#barang_id-' + activeSpecificationIndexModal).val(barang_id);
            $('#qrcode_barang-' + activeSpecificationIndexModal).val(qrcode_barang);
            $('#kode_barang-' + activeSpecificationIndexModal).val(kode_barang);
            $('#nama_barang-' + activeSpecificationIndexModal).val(nama_barang);
            $('#harga-' + activeSpecificationIndexModal).val(formatRupiahModal(harga));
            $('#diskon-' + activeSpecificationIndexModal).val(0); // ✅ Diskon default 0

            // Ambil jumlah dan hitung total
            var jumlah = parseFloat($('#jumlah-' + activeSpecificationIndexModal).val()) || 0;
            var total = harga * jumlah;
            $('#total-' + activeSpecificationIndexModal).val(formatRupiahModal(total));

            grandTotal();
            $('#tableBarangModal').modal('hide');
        }

        function filterTableModal() {
            var input, filter, table, tr, td, i, j, txtValue;
            input = document.getElementById("searchInputModal");
            filter = input.value.toUpperCase();
            table = document.getElementById("tablesModal");
            tr = table.getElementsByTagName("tr");

            for (i = 0; i < tr.length; i++) {
                var displayRow = false;

                // Loop through columns (td 1, 2, 3 and 4)
                for (j = 1; j <= 4; j++) {
                    td = tr[i].getElementsByTagName("td")[j];
                    if (td) {
                        txtValue = td.textContent || td.innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            displayRow = true;
                            break; // Break the loop if a match is found in any column
                        }
                    }
                }

                // Set the display style based on whether a match is found in any column
                tr[i].style.display = displayRow ? "" : "none";
            }
        }
        document.getElementById("searchInputModal").addEventListener("input", filterTableModal);

        function updateHargaTabel(golonganId) {
            // Cek apakah golonganId valid
            if (!golonganId) {
                console.log("Golongan tidak valid");
                return; // Tidak lakukan apa-apa jika golongan tidak valid
            }

            $('#tablesModal tbody tr').each(function() {
                var row = $(this);

                // Menyembunyikan semua kolom harga terlebih dahulu
                row.find('.harga_a, .harga_b, .harga_c, .harga_d, .harga_e').hide();

                // Menyimpan harga berdasarkan golongan
                var hargaA = row.data('harga_a');
                var hargaB = row.data('harga_b');
                var hargaC = row.data('harga_c');
                var hargaD = row.data('harga_d');
                var hargaE = row.data('harga_e');

                // Debugging untuk memeriksa nilai harga
                // console.log('Golongan: ' + golonganId);
                // console.log('Harga A: ' + hargaA);
                // console.log('Harga B: ' + hargaB);
                // console.log('Harga C: ' + hargaC);
                // console.log('Harga D: ' + hargaD);
                // console.log('Harga E: ' + hargaE);

                // Menampilkan kolom harga sesuai golongan yang dipilih
                if (golonganId === 'A') {
                    row.find('.harga_a').show().text(formatRupiahHarga(hargaA));
                } else if (golonganId === 'B') {
                    row.find('.harga_b').show().text(formatRupiahHarga(hargaB));
                } else if (golonganId === 'C') {
                    row.find('.harga_c').show().text(formatRupiahHarga(hargaC));
                } else if (golonganId === 'D') {
                    row.find('.harga_d').show().text(formatRupiahHarga(hargaD));
                } else if (golonganId === 'E') {
                    row.find('.harga_e').show().text(formatRupiahHarga(hargaE));
                }
            });
        }
        // Fungsi untuk format harga menjadi format Rupiah
        function formatRupiahHarga(angka) {
            if (angka == null || angka === 0) {
                return 'Rp0'; // Jika harga tidak ada atau 0, tampilkan Rp0
            }
            return 'Rp' + angka.toLocaleString('id-ID');
        }
    </script>
    {{-- akhir manual  --}}
    <script>
        function showCategoryModalpelanggan(selectedCategory) {
            $('#tablePelanggan').modal('show');
        }

        function getSelectedDatapelanggan(pelanggan_id, kodePelanggan, namaPelanggan, Golongan, Telp, Alamat) {
            // Set the values in the form fields
            document.getElementById('pelanggan_id').value = pelanggan_id;
            document.getElementById('kode_pelanggan').value = kodePelanggan;
            document.getElementById('nama_pelanggan').value = namaPelanggan;
            document.getElementById('golongan').value = Golongan;
            document.getElementById('telp').value = Telp;
            document.getElementById('alamat').value = Alamat;

            updateHargaTabel(Golongan); // Memanggil updateHargaTabel dengan golongan yang baru

            // Hapus data pembelian jika ada
            if (jumlah_ban > 0) {
                jumlah_ban = 0;
                $('#tabel-pembelian').empty();

                // Tambahkan pesan kosong
                var item_pembelian = '<tr>';
                item_pembelian += '<td class="text-center" colspan="9">- Barang belum ditambahkan -</td>';
                item_pembelian += '</tr>';
                $('#tabel-pembelian').html(item_pembelian);

                grandTotal();
            }

            // jika harga ingin di ubah tanpa menghapus pembelian barang 
            // updateHargaSemuaBarang();

            // Close the modal
            $('#tablePelanggan').modal('hide');
        }
    </script>

    {{-- addPesanan  --}}
    <script>
        var data_pembelian = @json(session('data_pembelians'));
        var jumlah_ban = 1;

        if (data_pembelian != null) {
            jumlah_ban = data_pembelian.length;
            $('#tabel-pembelian').empty();
            var urutan = 0;
            $.each(data_pembelian, function(key, value) {
                urutan = urutan + 1;
                itemPembelian(urutan, key, false, value);
            });
        }

        function addPesanan() {
            jumlah_ban = jumlah_ban + 1;

            if (jumlah_ban === 1) {
                $('#tabel-pembelian').empty();
            }

            itemPembelian(jumlah_ban, jumlah_ban - 1, true);
        }

        function removeBan(params) {
            jumlah_ban = jumlah_ban - 1;

            console.log(jumlah_ban);

            var tabel_pesanan = document.getElementById('tabel-pembelian');
            var pembelian = document.getElementById('pembelian-' + params);

            tabel_pesanan.removeChild(pembelian);

            if (jumlah_ban === 0) {
                var item_pembelian = '<tr>';
                item_pembelian += '<td class="text-center" colspan="9">- Barang belum ditambahkan -</td>';
                item_pembelian += '</tr>';
                $('#tabel-pembelian').html(item_pembelian);
            } else {
                var urutan = document.querySelectorAll('#urutan');
                for (let i = 0; i < urutan.length; i++) {
                    urutan[i].innerText = i + 1;
                }
            }
            grandTotal()
        }

        function itemPembelian(urutan, key, style, value = null) {
            var barang_id = '';
            var qrcode_barang = '';
            var kode_barang = '';
            var nama_barang = '';
            var harga = '';
            var jumlah = '';
            var diskon = '';
            var satuan_id = '';
            var total = '';

            if (value !== null) {
                barang_id = value.barang_id;
                qrcode_barang = value.qrcode_barang;
                kode_barang = value.kode_barang;
                nama_barang = value.nama_barang;
                harga = value.harga;
                jumlah = value.jumlah;
                diskon = value.diskon;
                satuan_id = value.satuan_id;
                total = value.total;
            }

            // urutan 
            var item_pembelian = '<tr id="pembelian-' + urutan + '">';
            item_pembelian += '<td style="width: 70px; font-size:14px" class="text-center" id="urutan-' + urutan + '">' +
                urutan + '</td>';

            // barang_id 
            item_pembelian += '<td hidden>';
            item_pembelian += '<div class="form-group">'
            item_pembelian += '<input type="text" class="form-control" id="barang_id-' + urutan +
                '" name="barang_id[]" value="' + barang_id + '" ';
            item_pembelian += '</div>';
            item_pembelian += '</td>';

            // qrcode_barang 
            item_pembelian += '<td hidden>';
            item_pembelian += '<div class="form-group">'
            item_pembelian += '<input style="font-size:14px" type="text" onclick="barangModal(' + urutan +
                ')" class="form-control qrcode_barang" readonly id="qrcode_barang-' +
                urutan +
                '" name="qrcode_barang[]" value="' + qrcode_barang + '" ';
            item_pembelian += '</div>';
            item_pembelian += '</td>';

            // kode_barang 
            item_pembelian += '<td>';
            item_pembelian += '<div class="form-group">'
            item_pembelian += '<input style="font-size:14px" type="text" onclick="barangModal(' + urutan +
                ')" class="form-control kode_barang" readonly id="kode_barang-' +
                urutan +
                '" name="kode_barang[]" value="' + kode_barang + '" ';
            item_pembelian += '</div>';
            item_pembelian += '</td>';


            // nama_barang 
            item_pembelian += '<td>';
            item_pembelian += '<div class="form-group">'
            item_pembelian += '<input style="font-size:14px" type="text" onclick="barangModal(' + urutan +
                ')" class="form-control" readonly id="nama_barang-' +
                urutan +
                '" name="nama_barang[]" value="' + nama_barang + '" ';
            item_pembelian += '</div>';
            item_pembelian += '</td>';

            // harga
            item_pembelian += '<td>';
            item_pembelian += '<div class="form-group">'
            item_pembelian +=
                '<input style="text-align: right" readonly type="text" class="form-control harga" id="harga-' + urutan +
                '" name="harga[]" onclick="barangModal(' + urutan +
                ')" value="' + harga + '" ';
            item_pembelian += '</div>';
            item_pembelian += '</td>';

            // jumlah
            item_pembelian += '<td>';
            item_pembelian += '<div  class="form-group">'
            item_pembelian += '<input style="font-size:14px" type="text" class="form-control jumlah" id="jumlah-' +
                urutan +
                '" name="jumlah[]" value="' + jumlah + '" ';
            item_pembelian += '</div>';
            item_pembelian += '</td>';

            // diskon
            item_pembelian += '<td>';
            item_pembelian += '<div class="form-group">';
            item_pembelian +=
                '<input style="font-size:14px; text-align: right" type="text" class="form-control diskon" id="diskon-' +
                urutan +
                '" name="diskon[]" value="' + formatRupiahItemBarang(diskon.toString()) + '">';
            item_pembelian += '</div>';
            item_pembelian += '</td>';

            // satuan_id 
            item_pembelian += '<td style="width: 220px">';
            item_pembelian += '<div style="font-size:14px" class="form-group">';
            item_pembelian += '<select class="form-control select2bs4" id="satuan_id-' + key +
                '" name="satuan_id[]">';
            item_pembelian += '<option value="">Pilih Satuan..</option>';
            item_pembelian += '@foreach ($satuans as $satuan_id)';
            item_pembelian +=
                '<option value="{{ $satuan_id->id }}" {{ $satuan_id->id == ' + satuan_id + ' ? 'selected' : '' }}>{{ $satuan_id->kode_satuan }}</option>';
            item_pembelian += '@endforeach';
            item_pembelian += '</select>';
            item_pembelian += '</div>';
            item_pembelian += '</td>';

            // total
            item_pembelian += '<td>';
            item_pembelian += '<div class="form-group">'
            item_pembelian += '<input style="text-align: right" onclick="barangModal(' + urutan +
                ')" type="text" class="form-control total" readonly id="total-' + urutan +
                '" name="total[]" value="' + total + '" readonly';
            item_pembelian += '</div>';
            item_pembelian += '</td>';

            item_pembelian += '<td style="width: 100px">';
            item_pembelian += '<button type="button" class="btn btn-primary btn-sm" onclick="barangModal(' + urutan + ')">';
            item_pembelian += '<i class="fas fa-plus"></i>';
            item_pembelian += '</button>';
            item_pembelian +=
                '<button style="margin-left:5px" type="button" class="btn btn-danger btn-sm" onclick="removeBan(' +
                urutan + ')">';
            item_pembelian += '<i class="fas fa-trash"></i>';
            item_pembelian += '</button>';
            item_pembelian += '</td>';
            item_pembelian += '</tr>';

            if (style) {
                select2(key);
            }

            $('#tabel-pembelian').append(item_pembelian);
            $('#satuan_id-' + key + '').val(satuan_id).attr('selected', true);

        }

        function select2(id) {
            $(function() {

                $('#satuan_id-' + id).select2({
                    theme: 'bootstrap4'
                });

            });
        }

        function formatRupiahItemBarang(angka, prefix) {
            let number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? prefix + rupiah : '');
        }

        $(document).on('input', '.diskon', function() {
            let val = $(this).val().replace(/\./g, '').replace(/[^0-9]/g, '');
            $(this).val(formatRupiahItemBarang(val));
        });
    </script>
    {{-- akhir addPesanan  --}}
    <script>
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                document.getElementById('addPesananBtn').click();
            }
        });
    </script>

    <script>
        // Event listener untuk input harga dan jumlah
        function formatRupiah1(angka) {
            return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        $(document).on("input", ".harga, .jumlah, .diskon", function() {
            var currentRow = $(this).closest('tr');

            // Ambil nilai harga dan hapus titik ribuan
            var hargaText = currentRow.find(".harga").val();
            var harga = parseFloat(hargaText.replace(/\./g, '')) || 0;

            // Ambil jumlah
            var jumlah = parseFloat(currentRow.find(".jumlah").val()) || 0;

            // Ambil diskon dan hapus titik ribuan
            var diskonText = currentRow.find(".diskon").val();
            var diskon = parseFloat(diskonText.replace(/\./g, '')) || 0;

            // Hitung total (harga * jumlah - diskon)
            var total = (harga * jumlah) - diskon;
            if (total < 0) total = 0; // Tidak boleh minus

            // Set hasil total dalam format rupiah
            currentRow.find(".total").val(formatRupiah1(total));

            // Panggil hitung grand total
            grandTotal();
        });


        function grandTotal() {
            let totalSemua = 0;

            // Menjumlahkan semua nilai dari input dengan ID diawali 'total-'
            $("[id^='total-']").each(function() {
                let val = $(this).val().toString().replace(/\./g, '').replace(/,/g, '');
                let angka = parseFloat(val) || 0;
                totalSemua += angka;
            });

            let totalPPN = 0;

            // Cek kategori (PPN / NON PPN)
            let kategori = $('#kategori').val();

            if (kategori === 'PPN') {
                totalPPN = Math.round(totalSemua * 0.11);
            } else {
                totalPPN = 0;
            }

            // Hitung grand total
            let grandTotal = totalSemua + totalPPN;

            // Tampilkan hasilnya ke input form
            $('#total_harga').val(formatRupiahsss(totalSemua));
            $('#ppn').val(formatRupiahsss(totalPPN));
            $('#grand_total').val(formatRupiahsss(grandTotal));
        }

        // Jalankan grandTotal() setiap kali ada perubahan di barang atau kuantitas atau kategori
        $(document).on('input', "[id^='total-']", function() {
            grandTotal();
        });

        $(document).on('change', '#kategori', function() {
            grandTotal();
        });

        // Jalankan grandTotal() setiap kali ada perubahan di barang atau kuantitas
        $(document).on('input', "[id^='total-']", function() {
            grandTotal();
        });

        // Fungsi formatRupiahsss terpisah agar bisa digunakan kembali
        function formatRupiahsss(angka) {
            if (!angka) return '0';
            angka = parseFloat(angka.toString().replace(/\D/g, '')) || 0;
            return new Intl.NumberFormat('id-ID').format(angka);
        }

        // Fungsi untuk memformat angka ke dalam format rupiah (dengan titik ribuan)
        function formatRupiah(angka) {
            return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
        $(document).on("input", ".rupiah", function() {
            let input = $(this).val();
            input = input.replace(/\D/g, "");
            input = formatRupiah(input);
            $(this).val(input);
        });
    </script>
    {{-- validasi kosong  --}}
    <script>
        $(document).ready(function() {
            $('form').on('submit', function(e) {
                var pelangganName = $('#kode_pelanggan').val().trim();
                var barangValid = true;
                var nominalValid = true;

                // Validasi pelanggan
                if (pelangganName === "") {
                    e.preventDefault();
                    $('#error-pelanggan').text('Pilih Pelanggan.');
                    $('#kode_pelanggan').addClass('is-invalid');
                } else {
                    $('#error-pelanggan').text('');
                    $('#kode_pelanggan').removeClass('is-invalid');
                }

                // Validasi data barang (loop tiap baris untuk nama barang)
                $('input[name^="barang"]').each(function() {
                    var val = $(this).val().trim();
                    if (val === "") {
                        barangValid = false;
                        $(this).addClass('is-invalid');
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });

                // Validasi untuk kode_barang
                $('input[name^="kode_barang"]').each(function() {
                    var val = $(this).val().trim();
                    if (val === "") {
                        barangValid = false;
                        $(this).addClass('is-invalid');
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });

                // Validasi untuk jumlah barang
                $('input[name^="jumlah"]').each(function() {
                    var val = $(this).val().trim();
                    if (val === "") {
                        barangValid = false;
                        $(this).addClass('is-invalid');
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });

                // Validasi untuk satuan_id (select)
                $('select[name^="satuan_id"]').each(function() {
                    var val = $(this).val().trim();
                    if (val === "") {
                        barangValid = false;
                        $(this).addClass('is-invalid');
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });

                // Validasi kategori
                var Kategori = $('#kategori').val().trim();
                if (Kategori === "") {
                    e.preventDefault();
                    KategoriValid = false;
                    $('#error-kategori').text('Pilih Kategori.');
                    $('#kategori').addClass('is-invalid');
                } else {
                    $('#error-kategori').text('');
                    $('#kategori').removeClass('is-invalid');
                }


                // Jika barang tidak valid, munculkan alert barang
                if (!barangValid) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Data Barang Tidak Lengkap',
                        text: 'Mohon lengkapi semua data barang sebelum menyimpan!',
                        confirmButtonText: 'OK'
                    });
                }

                // Jika semua valid, baru sembunyikan tombol & tampilkan loading
                if (pelangganName !== "" && barangValid && KategoriValid) {
                    // Menyembunyikan tombol Simpan dan Reset, serta menampilkan loading
                    $('#btnSimpan').hide();
                    $('#btnReset').hide();
                    $('#loading').show();
                } else {
                    // Jangan tampilkan loading dan sembunyikan tombol jika ada yang tidak valid
                    $('#btnSimpan').show();
                    $('#btnReset').show();
                    $('#loading').hide();
                }
            });
        });
    </script>

    {{-- akhir validasi kosong  --}}

    {{-- scan  --}}
    <script>
        var currentRowIndex = null;

        function handleScan() {
            var pelangganId = $('#pelanggan_id').val();
            if (!pelangganId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pilih Pelanggan',
                    text: 'Silakan pilih pelanggan terlebih dahulu sebelum melakukan scan.',
                });

                $('#scanInput').val(''); // Kosongkan input scan
                return; // Stop proses jika pelanggan belum dipilih
            }
            var scanValue = $('#scanInput').val();

            if (scanValue) {
                function formatAngkarupe(angka) {
                    return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                }

                var found = false;
                // Lanjutkan proses scan seperti biasa
                $('.kode_barang').each(function(index) {
                    if ($(this).val().toUpperCase() === scanValue.toUpperCase()) {
                        var rowIndex = $(this).attr('id').split('-')[1];
                        var jumlahInput = $('#jumlah-' + rowIndex);
                        var jumlah = parseInt(jumlahInput.val()) || 0;
                        jumlahInput.val(jumlah + 1);

                        var harga = parseFloat($('#harga-' + rowIndex).val().replace(/\./g, '')) || 0;

                        // Ambil nilai diskon, default ke 0 jika kosong
                        var diskon = parseFloat($('#diskon-' + rowIndex).val().replace(/\./g, '')) || 0;

                        // Hitung total = (harga * jumlah) - diskon
                        var total = ((jumlah + 1) * harga) - diskon;
                        if (total < 0) total = 0; // Total tidak boleh minus

                        $('#total-' + rowIndex).val(formatAngkarupe(total)); // Format hasil total
                        grandTotal();

                        found = true;
                        $('#scanInput').val('');
                        return false;
                    }
                });

                if (!found) {
                    addPesanan();
                    currentRowIndex = jumlah_ban;

                    $('#searchInput').val(scanValue);
                    filterTable();

                    $('#tableBarang').show();

                    setTimeout(function() {
                        var visibleRows = $('#tables tbody tr:visible');

                        if (visibleRows.length === 1) {
                            visibleRows.find('button').trigger('click');
                            $('#scanInput').val('');
                        } else {
                            // Cek jika kode_barang di baris currentRowIndex kosong setelah pencarian
                            var kodeBarangInput = $('#kode_barang-' + currentRowIndex);
                            if (kodeBarangInput.val().trim() === '') {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Barang Tidak Ditemukan',
                                    text: 'Barang yang anda cari tidak ada dalam tabel.',
                                    timer: 1500,
                                    showConfirmButton: false
                                });

                                removeBan(currentRowIndex); // Hapus baris jika kosong
                            }
                        }
                        $('#scanInput').val(''); // Kosongkan input scan
                    }, 300);
                }

            }
        }



        // Fungsi untuk memfilter tabel berdasarkan input pencarian
        function filterTable() {
            var input = document.getElementById("searchInput");
            var filter = input.value.toUpperCase();
            var table = document.getElementById("tables");
            var tr = table.getElementsByTagName("tr");

            for (var i = 0; i < tr.length; i++) {
                var displayRow = false;

                for (var j = 1; j <= 3; j++) {
                    var td = tr[i].getElementsByTagName("td")[j];
                    if (td) {
                        var txtValue = td.textContent || td.innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            displayRow = true;
                            break;
                        }
                    }
                }

                tr[i].style.display = displayRow ? "" : "none";
            }
        }

        var activeSpecificationIndex = 0;

        function barang(param) {
            var pelangganId = $('#pelanggan_id').val();

            if (!pelangganId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pilih Pelanggan',
                    text: 'Silakan pilih pelanggan terlebih dahulu sebelum memilih barang.',
                });
                return;
            }

            activeSpecificationIndex = param;
            $('#tableBarang').show(); // Tampilkan div tableBarang
        }

        // Fungsi untuk memilih barang dari table
        function getBarang(index) {
            var row = $('#tables tbody tr').eq(index);
            var barang_id = row.data('barang_id');
            var qrcode_barang = row.data('qrcode_barang');
            var kode_barang = row.data('kode_barang');
            var nama_barang = row.data('nama_barang');

            // Ambil semua harga A-E dari data attribute
            var harga_a = parseFloat(row.data('harga_a')) || 0;
            var harga_b = parseFloat(row.data('harga_b')) || 0;
            var harga_c = parseFloat(row.data('harga_c')) || 0;
            var harga_d = parseFloat(row.data('harga_d')) || 0;
            var harga_e = parseFloat(row.data('harga_e')) || 0;

            // Ambil nilai golongan dari input
            var golongan = $('#golongan').val().trim().toUpperCase();

            // Tentukan harga berdasarkan golongan
            var harga = 0;
            switch (golongan) {
                case 'A':
                    harga = harga_a;
                    break;
                case 'B':
                    harga = harga_b;
                    break;
                case 'C':
                    harga = harga_c;
                    break;
                case 'D':
                    harga = harga_d;
                    break;
                case 'E':
                    harga = harga_e;
                    break;
                default:
                    harga = 0;
            }

            function formatAngka(angka) {
                return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            if (currentRowIndex !== null) {
                $('#barang_id-' + currentRowIndex).val(barang_id);
                $('#qrcode_barang-' + currentRowIndex).val(qrcode_barang);
                $('#kode_barang-' + currentRowIndex).val(kode_barang);
                $('#nama_barang-' + currentRowIndex).val(nama_barang);
                $('#harga-' + currentRowIndex).val(formatAngka(harga));
                $('#jumlah-' + currentRowIndex).val(1);
                $('#diskon-' + currentRowIndex).val(0); // ✅ Diskon default 0

                var total = harga * 1;
                $('#total-' + currentRowIndex).val(formatAngka(total));

                grandTotal();
                closeTableBarang();
            }

            currentRowIndex = null;
        }


        // Fungsi untuk menutup tableBarang
        function closeTableBarang() {
            $('#tableBarang').hide(); // Menyembunyikan div tableBarang
        }

        // Event listener untuk filter saat ketik di input pencarian
        document.getElementById("searchInput").addEventListener("input", filterTable);
    </script>
    {{-- akhir scan  --}}

    {{-- jika back ke halaman ini  --}}
    <script>
        window.addEventListener('pageshow', function(event) {
            // Deteksi kembali dari history (bisa via persisted atau back/forward)
            var isBackForward = event.persisted || (performance.getEntriesByType("navigation")[0] && performance
                .getEntriesByType("navigation")[0].type === "back_forward");

            if (isBackForward) {
                console.log('Page restored from back/forward cache, resetting form.');

                // Reset semua input form
                document.querySelector('form').reset();

                // Reset select2 jika ada
                if ($('#pelanggan_id').length) {
                    $('#pelanggan_id').val('').trigger('change');
                }

                if ($('#golongan').length) {
                    $('#golongan').val('').trigger('change');
                }

                // Bersihkan isi tabel pesanan
                if ($('#tabel-pembelian').length) {
                    $('#tabel-pembelian').html(
                        '<tr><td class="text-center" colspan="9">- Barang belum ditambahkan -</td></tr>'
                    );
                }

                // Menyembunyikan tombol loading dan menampilkan tombol Simpan dan Reset
                $('#loading').hide();
                $('#btnSimpan').show();
                $('#btnReset').show();
            }
        });

        // Menangani pengaturan tombol saat form di-reset atau setelah disubmit
        function handleFormSubmit() {
            // Sembunyikan tombol simpan dan reset, tampilkan loading
            $('#btnSimpan').hide();
            $('#btnReset').hide();
            $('#loading').show();
        }

        // Event listener untuk form submit atau reset
        document.querySelector('form').addEventListener('submit', handleFormSubmit);
    </script>
    {{-- akhir dari back      --}}

    {{-- update harga saat golongan di ganti  --}}
    {{-- <script>
        function updateHargaSemuaBarang() {
            var golongan = $('#golongan').val().trim().toUpperCase();
            var rowCount = $('#tables tbody tr').length;

            for (var i = 0; i < rowCount; i++) {
                var row = $('#tables tbody tr').eq(i);

                var harga_a = parseFloat(row.data('harga_a')) || 0;
                var harga_b = parseFloat(row.data('harga_b')) || 0;
                var harga_c = parseFloat(row.data('harga_c')) || 0;
                var harga_d = parseFloat(row.data('harga_d')) || 0;
                var harga_e = parseFloat(row.data('harga_e')) || 0;

                var harga = 0;
                switch (golongan) {
                    case 'A':
                        harga = harga_a;
                        break;
                    case 'B':
                        harga = harga_b;
                        break;
                    case 'C':
                        harga = harga_c;
                        break;
                    case 'D':
                        harga = harga_d;
                        break;
                    case 'E':
                        harga = harga_e;
                        break;
                }

                // Update field harga
                $('#harga-' + i).val(formatAngka(harga));

                // Update total jika jumlah sudah terisi
                var jumlah = parseFloat($('#jumlah-' + i).val()) || 0;
                var total = harga * jumlah;
                $('#total-' + i).val(formatAngka(total));
            }

            grandTotal(); // Perbarui total keseluruhan
        }

        function formatAngka(angka) {
            return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
    </script> --}}

@endsection
