@extends('layouts.app')

@section('title', 'Inquery Penjualan')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                </div><!-- /.col -->
                <div class="col-sm-6">

                </div>
            </div>
        </div>
    </div>


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
            <form action="{{ url('admin/inquery-pembelian/' . $inquery->id) }}" method="POST" enctype="multipart/form-data"
                autocomplete="off">
                @csrf
                @method('put')
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Detail Pelanggan</h3>
                        <div class="float-right">
                        </div>
                    </div>

                    <div class="card-body">

                        <div class="form-group">
                            <label class="form-label" for="kategori">Kategori</label>
                            <select class="form-control" id="kategori" name="kategori">
                                <option value="">- Pilih -</option>
                                <option value="PPN"
                                    {{ old('kategori', $inquery->kategori) == 'PPN' ? 'selected' : null }}>
                                    PPN</option>
                                <option value="NON PPN"
                                    {{ old('kategori', $inquery->kategori) == 'NON PPN' ? 'selected' : null }}>
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
                                    value="{{ old('pelanggan_id', $inquery->pelanggan_id) }}">
                            </div>

                            <!-- Kode Pelanggan dan Button -->
                            <div class="col-md-6">
                                <label class="form-label" for="kode_pelanggan">Kode Pelanggan *</label>
                                <div class="input-group mb-3">
                                    <input onclick="showCategoryModalpelanggan(this.value)"
                                        class="form-control @error('kode_pelanggan') is-invalid @enderror"
                                        id="kode_pelanggan" name="kode_pelanggan" type="text"
                                        value="{{ old('kode_pelanggan', $inquery->pelanggan->kode_pelanggan ?? null) }}"
                                        readonly />
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
                                    id="nama_pelanggan" name="nama_pelanggan"
                                    value="{{ old('nama_pelanggan', $inquery->pelanggan->nama_pelanggan ?? null) }}"
                                    readonly>
                            </div>

                            <!-- Golongan Pelanggan -->
                            <div class="col-md-6">
                                <label for="golongan">Golongan Pelanggan</label>
                                <input onclick="showCategoryModalpelanggan(this.value)" type="text" class="form-control"
                                    id="golongan" name="golongan"
                                    value="{{ old('golongan', $inquery->pelanggan->golongan->nama_golongan ?? null) }}"
                                    readonly>
                            </div>

                            <!-- No. Telepon -->
                            <div class="col-md-6">
                                <label for="telp">No. Telepon</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">+62</span>
                                    </div>
                                    <input onclick="showCategoryModalpelanggan(this.value)" type="text" id="telp"
                                        name="telp" class="form-control"
                                        value="{{ old('telp', $inquery->pelanggan->telp ?? null) }}" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Row Kedua: Alamat -->
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label for="alamat">Alamat</label>
                                <textarea onclick="showCategoryModalpelanggan(this.value)" class="form-control" id="alamat" name="alamat"
                                    rows="2" readonly>{{ old('alamat', $inquery->pelanggan->alamat ?? null) }}</textarea>
                            </div>
                        </div>


                    </div>
                    <!-- /.card-header -->
                </div>
                <div>
                    <div class="card" id="form_biayatambahan">
                        <div class="card-header">
                            <h3 class="card-title">Tambahkan Barang <span>
                                </span></h3>
                            <div class="float-right">
                                <button type="button" class="btn btn-primary btn-sm" onclick="addPesanan()">
                                    <i class="fas fa-plus"></i>
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
                                        <th>Jumlah</th>
                                        <th>Diskon</th>
                                        <th>Satuan</th>
                                        <th>Total</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody id="tabel-pembelian">
                                    @foreach ($details as $detail)
                                        <tr id="pembelian-{{ $loop->index }}">
                                            <td style="width: 70px; font-size:14px" class="text-center" id="urutan">
                                                {{ $loop->index + 1 }}
                                            </td>
                                            <td hidden>
                                                <div class="form-group">
                                                    <input type="text" class="form-control"
                                                        id="id-{{ $loop->index }}" name="detail_ids[]"
                                                        value="{{ $detail['id'] }}">
                                                </div>
                                            </td>
                                            <td hidden>
                                                <div class="form-group">
                                                    <input style="font-size:14px" type="text" class="form-control"
                                                        id="barang_id-{{ $loop->index }}" name="barang_id[]"
                                                        value="{{ $detail['barang_id'] }}">
                                                </div>
                                            </td>
                                            <td hidden onclick="barangModal({{ $loop->index }})">
                                                <div class="form-group">
                                                    <input style="font-size:14px" type="text" readonly
                                                        class="form-control" id="qrcode_barang-{{ $loop->index }}"
                                                        name="qrcode_barang[]" value="{{ $detail['qrcode_barang'] }}">
                                                </div>
                                            </td>
                                            <td onclick="barangModal({{ $loop->index }})">
                                                <div class="form-group">
                                                    <input style="font-size:14px" type="text" readonly
                                                        class="form-control" id="kode_barang-{{ $loop->index }}"
                                                        name="kode_barang[]" value="{{ $detail['kode_barang'] }}">
                                                </div>
                                            </td>
                                            <td onclick="barangModal({{ $loop->index }})">
                                                <div class="form-group">
                                                    <input style="font-size:14px" type="text" readonly
                                                        class="form-control" id="nama_barang-{{ $loop->index }}"
                                                        name="nama_barang[]" value="{{ $detail['nama_barang'] }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input readonly style="font-size:14px" type="text"
                                                        class="form-control harga" id="harga-0" name="harga[]"
                                                        data-row-id="0"
                                                        value="{{ number_format($detail['harga'], 0, ',', '.') }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input style="font-size:14px" type="number"
                                                        class="form-control jumlah" id="jumlah-0" name="jumlah[]"
                                                        data-row-id="0" value="{{ $detail['jumlah'] }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input style="font-size:14px" type="number"
                                                        class="form-control diskon" id="diskon-0" name="diskon[]"
                                                        data-row-id="0" value="{{ $detail['diskon'] }}">
                                                </div>
                                            </td>
                                            <td style="width: 150px">
                                                <div class="form-group">
                                                    <div class="form-group">
                                                        <select style="font-size:14px" class="form-control"
                                                            id="satuan_id-{{ $loop->index }}" name="satuan_id[]">
                                                            <option value="">Pilih Satuan..</option>
                                                            @foreach ($satuans as $satuan_id)
                                                                <option value="{{ $satuan_id->id }}"
                                                                    {{ old('satuan_id.' . $loop->parent->index, $detail['satuan_id']) == $satuan_id->id ? 'selected' : '' }}>
                                                                    {{ $satuan_id->kode_satuan }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input readonly style="font-size:14px" type="text"
                                                        oninput="formatRupiahHtml(this)" class="form-control total"
                                                        id="total-0" name="total[]" data-row-id="0"
                                                        value="{{ number_format($detail['total'], 0, ',', '.') }}">
                                                </div>
                                            </td>
                                            <td style="width: 100px">
                                                <button type="button" class="btn btn-primary btn-sm"
                                                    onclick="barangModal({{ $loop->index }})">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                                <button style="margin-left:5px" type="button"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="removePesanan({{ $loop->index }}, {{ $detail['id'] }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
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
                                        <input style="text-align: right" type="text" class="form-control"
                                            id="ppn" name="ppn" readonly>
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
                                                                        <button type="button"
                                                                            class="btn btn-primary btn-sm"
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
                        <div class="card-footer text-right">
                            <button type="reset" class="btn btn-secondary">Reset</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>


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
    </section>

    {{-- swetaalert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


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

    <script>
        var activeSpecificationIndexModal = 0;

        function barangModal(param) {
            activeSpecificationIndexModal = param;
            // Show the modal and filter rows if necessary
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

        // Function to filter the table rows based on the search input
        function filterTableModal() {
            var input, filter, table, tr, td, i, j, txtValue;
            input = document.getElementById("searchInputModal");
            filter = input.value.toUpperCase();
            table = document.getElementById("tablesModal");
            tr = table.getElementsByTagName("tr");

            for (i = 0; i < tr.length; i++) {
                var displayRow = false;

                // Loop through columns (td 1, 2, and 3)
                for (j = 1; j <= 3; j++) {
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

        $(document).ready(function() {
            var selectedGolongan = $('#golongan')
                .val(); // ambil nilai default dari select golongan (atau sesuai kebutuhan)
            updateHargaTabel(selectedGolongan);
        });

        // Fungsi untuk format harga menjadi format Rupiah
        function formatRupiahHarga(angka) {
            if (angka == null || angka === 0) {
                return 'Rp0'; // Jika harga tidak ada atau 0, tampilkan Rp0
            }
            return 'Rp' + angka.toLocaleString('id-ID');
        }

        $(document).on("input", ".harga, .jumlah, .diskon", function() {
            var currentRow = $(this).closest('tr');

            // Hapus titik agar bisa dihitung
            var harga = parseFloat(currentRow.find(".harga").val().replace(/\./g, '')) || 0;
            var jumlah = parseFloat(currentRow.find(".jumlah").val().replace(/\./g, '')) || 0;
            var diskon = parseFloat(currentRow.find(".diskon").val().replace(/\./g, '')) || 0;

            var total = harga * jumlah - diskon;

            // Format hasil total ke ribuan
            currentRow.find(".total").val(total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));

            grandTotal();
        });
    </script>

    <script>
        var data_pembelian = @json(session('data_pembelians'));
        var jumlah_ban = 1;

        if (data_pembelian != null) {
            jumlah_ban = data_pembelian.length;
            $('#tabel-pembelian').empty();
            var urutan = 0;
            $.each(data_pembelian, function(key, value) {
                urutan = urutan + 1;
                itemPembelian(urutan, key, value);
            });
        }

        function updateUrutan() {
            var urutan = document.querySelectorAll('#urutan');
            for (let i = 0; i < urutan.length; i++) {
                urutan[i].innerText = i + 1;
            }
        }

        var counter = 0;

        function addPesanan() {
            counter++;
            jumlah_ban = jumlah_ban + 1;

            if (jumlah_ban === 1) {
                $('#tabel-pembelian').empty();
            } else {
                // Find the last row and get its index to continue the numbering
                var lastRow = $('#tabel-pembelian tr:last');
                var lastRowIndex = lastRow.find('#urutan').text();
                jumlah_ban = parseInt(lastRowIndex) + 1;
            }

            console.log('Current jumlah_ban:', jumlah_ban);
            itemPembelian(jumlah_ban, jumlah_ban - 1);
            updateUrutan();
        }

        function removePesanan(identifier) {
            var row = $('#pembelian-' + identifier);
            var detailId = row.find("input[name='detail_ids[]']").val();

            row.remove();

            if (detailId) {
                $.ajax({
                    url: "{{ url('admin/inquery-penjualan/deletedetailpenjualan/') }}/" + detailId,
                    type: "POST",
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log('Data deleted successfully');
                    },
                    error: function(error) {
                        console.error('Failed to delete data:', error);
                    }
                });
            }
            grandTotal()
            updateUrutan();
        }

        function itemPembelian(identifier, key, value = null) {
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
            var item_pembelian = '<tr id="pembelian-' + key + '">';
            item_pembelian += '<td style="width: 70px; font-size:14px" class="text-center" id="urutan">' + key + '</td>';

            // barang_id 
            item_pembelian += '<td hidden>';
            item_pembelian += '<div class="form-group">'
            item_pembelian += '<input type="text" class="form-control" style="font-size:14px" id="barang_id-' +
                key +
                '" name="barang_id[]" value="' + barang_id + '" ';
            item_pembelian += '</div>';
            item_pembelian += '</td>';

            // qrcode_barang 
            item_pembelian += '<td hidden onclick="barangModal(' + key +
                ')">';
            item_pembelian += '<div class="form-group">'
            item_pembelian += '<input type="text" class="form-control" readonly style="font-size:14px" id="qrcode_barang-' +
                key +
                '" name="qrcode_barang[]" value="' + qrcode_barang + '" ';
            item_pembelian += '</div>';
            item_pembelian += '</td>';

            // kode_barang 
            item_pembelian += '<td onclick="barangModal(' + key +
                ')">';
            item_pembelian += '<div class="form-group">'
            item_pembelian += '<input type="text" class="form-control" readonly style="font-size:14px" id="kode_barang-' +
                key +
                '" name="kode_barang[]" value="' + kode_barang + '" ';
            item_pembelian += '</div>';
            item_pembelian += '</td>';

            // nama_barang 
            item_pembelian += '<td onclick="barangModal(' + key +
                ')">';
            item_pembelian += '<div class="form-group">'
            item_pembelian += '<input type="text" class="form-control" readonly style="font-size:14px" id="nama_barang-' +
                key +
                '" name="nama_barang[]" value="' + nama_barang + '" ';
            item_pembelian += '</div>';
            item_pembelian += '</td>';

            // harga
            item_pembelian += '<td>';
            item_pembelian += '<div class="form-group">'
            item_pembelian +=
                '<input readonly type="text" style="font-size:14px" class="form-control harga" id="harga-' +
                key +
                '" name="harga[]" value="' + harga + '" ';
            item_pembelian += '</div>';
            item_pembelian += '</td>';

            // jumlah
            item_pembelian += '<td>';
            item_pembelian += '<div class="form-group">'
            item_pembelian += '<input type="text" style="font-size:14px" class="form-control jumlah" id="jumlah-' + key +
                '" name="jumlah[]" value="' + jumlah + '" ';
            item_pembelian += '</div>';
            item_pembelian += '</td>';


            // diskon
            item_pembelian += '<td>';
            item_pembelian += '<div class="form-group">'
            item_pembelian += '<input type="text" style="font-size:14px" class="form-control diskon" id="diskon-' + key +
                '" name="diskon[]" value="' + diskon + '" ';
            item_pembelian += '</div>';
            item_pembelian += '</td>';

            // satuan_id 
            item_pembelian += '<td style="width: 150px;>';
            item_pembelian += '<div class="form-group">';
            item_pembelian += '<select style="font-size:14px" class="form-control select2bs4" id="satuan_id-' + key +
                '" name="satuan_id[]">';
            item_pembelian += '<option value="">Pilih Satuan..</option>';
            item_pembelian += '@foreach ($satuans as $satuan_id)';
            item_pembelian +=
                '<option value="{{ $satuan_id->id }}" {{ $satuan_id->id == ' + satuan_id + ' ? 'selected' : '' }}>{{ $satuan_id->kode_satuan }}</option>';
            item_pembelian += '@endforeach';
            item_pembelian += '</select>';
            item_pembelian += '</div>';
            item_pembelian += '</td>';
            item_pembelian += '</td>'

            // total
            item_pembelian += '<td>';
            item_pembelian += '<div class="form-group">'
            item_pembelian += '<input readonly type="text" style="font-size:14px" class="form-control total" id="total-' +
                key +
                '" name="total[]" value="' + total + '" readonly';
            item_pembelian += '</div>';
            item_pembelian += '</td>';


            item_pembelian += '<td style="width: 100px">';
            item_pembelian += '<button type="button" class="btn btn-primary btn-sm" onclick="barangModal(' + key +
                ')">';
            item_pembelian += '<i class="fas fa-plus"></i>';
            item_pembelian += '</button>';
            item_pembelian +=
                '<button style="margin-left:10px" type="button" class="btn btn-danger btn-sm" onclick="removePesanan(' +
                key + ')">';
            item_pembelian += '<i class="fas fa-trash"></i>';
            item_pembelian += '</button>';
            item_pembelian += '</td>';
            item_pembelian += '</tr>';

            $('#tabel-pembelian').append(item_pembelian);

            if (value !== null) {
                $('#satuan_id-' + key).val(value.satuan_id);
            }
        }
    </script>

    <script>
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

        $('body').on('input', 'input[name^="total"]', function() {
            grandTotal();
        });

        $(document).on('change', '#kategori', function() {
            grandTotal();
        });

        // Panggil fungsi saat halaman dimuat untuk menginisialisasi grand total
        $(document).ready(function() {
            grandTotal();
        });

        function formatRupiahsss(angka) {
            if (!angka) return '0';
            angka = parseFloat(angka.toString().replace(/\D/g, '')) || 0;
            return new Intl.NumberFormat('id-ID').format(angka);
        }
    </script>

    <script>
        function formatRupiahHtml(el) {
            let value = el.value.replace(/\D/g, ''); // hapus semua karakter non-digit
            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // tambahkan titik pemisah ribuan
            el.value = value;
        }
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

                            if (kodeBarangInput.length && (kodeBarangInput.val() || '').trim() === '') {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Barang Tidak Ditemukan',
                                    text: 'Barang yang anda cari tidak ada dalam tabel.',
                                    timer: 1500,
                                    showConfirmButton: false
                                });

                                removePesanan(currentRowIndex); // Hapus baris jika kosong
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


@endsection
