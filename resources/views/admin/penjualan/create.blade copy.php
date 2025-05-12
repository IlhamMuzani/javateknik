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
                            <textarea onclick="showCategoryModalpelanggan(this.value)" class="form-control" id="alamat"
                                name="alamat" rows="2" readonly>{{ old('alamat') }}</textarea>
                        </div>
                    </div>


                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tambah Barang</h3>
                    <div class="float-right">
                        <button type="button" class="btn btn-primary btn-sm" id="addPesananBtn" onclick="addPesanan()">
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
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Satuan</th>
                                <th>Total</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody id="tabel-pembelian">
                            <tr id="pembelian-0">
                                <td style="width: 70px; font-size:14px" class="text-center" id="urutan">1</td>
                                <td hidden>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="barang_id-0" name="barang_id[]">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input style="font-size:14px" type="text" class="form-control kode_barang"
                                            id="kode_barang-0" readonly onclick="barang(0)" name="kode_barang[]">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input style="font-size:14px" type="text" class="form-control"
                                            id="nama_barang-0" readonly onclick="barang(0)" name="nama_barang[]">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input style="text-align: right" type="text" onclick="barang(0)" readonly
                                            class="form-control harga" id="harga-0" name="harga[]" data-row-id="0">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input style="font-size:14px" type="number" class="form-control jumlah"
                                            id="jumlah-0" name="jumlah[]" data-row-id="0">
                                    </div>
                                </td>
                                <td style="width: 220px">
                                    <div style="font-size:14px" class="form-group">
                                        <select class="select2bs4 select21-hidden-accessible" name="satuan_id[]"
                                            data-placeholder="Pilih Satuan.." style="width: 100%;" data-select21-id="23"
                                            tabindex="-1" aria-hidden="true" id="satuan_id-0">
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
                                        <input style="text-align: right" onclick="barang(0)" type="text"
                                            class="form-control total" id="total-0" name="total[]" readonly>
                                    </div>
                                </td>
                                <td style="width: 100px">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="barang(0)">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    <button style="margin-left:5px" type="button" class="btn btn-danger btn-sm"
                                        onclick="removeBan(0)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-body">
                    <!-- Tambahkan setelah </table> -->
                    <div class="row mt-4">
                        <!-- Total Harga -->
                        <div class="col-md-4 offset-md-8">
                            <div class="form-group">
                                <label for="total_harga">Total Harga</label>
                                <input style="text-align: right" type="text" class="form-control" id="total_harga"
                                    name="total_harga" readonly>
                            </div>
                        </div>

                        <!-- Nominal Pembayaran -->
                        <div class="col-md-4 offset-md-8">
                            <div class="form-group">
                                <label for="nominal_pembayaran">Nominal Pembayaran</label>
                                <input style="text-align: right" type="text" class="form-control rupiah"
                                    id="nominal_pembayaran" name="nominal_pembayaran">
                                <div id="error-nominal" class="text-danger mt-1" style="font-size: 0.9em;">
                                </div> <!-- Pesan error -->
                            </div>
                        </div>

                        <!-- Grand Total -->
                        <div class="col-md-4 offset-md-8">
                            <div class="form-group">
                                <label for="grand_total">Grand Total</label>
                                <input style="text-align: right" type="text" class="form-control" id="grand_total"
                                    name="grand_total" readonly>
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

    <div class="modal fade" id="tableBarang" data-backdrop="static">
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
                        <input type="text" id="searchInput" class="form-control" placeholder="Search...">
                    </div>
                    <table id="tables" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Kode Barang</th>
                                <th>Nama barang</th>
                                <th>Merek</th>
                                {{-- <th>Type</th>
                                    <th>Bagian</th> --}}
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($barangs as $barang)
                            <tr data-barang_id="{{ $barang->id }}" data-kode_barang="{{ $barang->kode_barang }}"
                                data-nama_barang="{{ $barang->nama_barang }}" data-harga="{{ $barang->harga }}"
                                data-param="{{ $loop->index }}" onclick="getBarang({{ $loop->index }})">
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $barang->kode_barang }}</td>
                                <td>{{ $barang->nama_barang }}</td>
                                <td>{{ $barang->merek->nama_merek ?? null }}</td>
                                <td> {{ number_format($barang->harga, 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    <button type="button" id="btnTambah" class="btn btn-primary btn-sm"
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

</section>
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
    // document.getElementById('umur').value = Umur;
    document.getElementById('alamat').value = Alamat;

    // Close the modal (if needed)
    $('#tablePelanggan').modal('hide');
}
</script>
<script>
// Function to filter the table rows based on the search input

function filterTable() {
    var input, filter, table, tr, td, i, j, txtValue;
    input = document.getElementById("searchInput");
    filter = input.value.toUpperCase();
    table = document.getElementById("tables");
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
document.getElementById("searchInput").addEventListener("input", filterTable);


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
        item_pembelian += '<td class="text-center" colspan="5">- Barang belum ditambahkan -</td>';
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
    var kode_barang = '';
    var nama_barang = '';
    var harga = '';
    var jumlah = '';
    var satuan_id = '';
    var total = '';

    if (value !== null) {
        barang_id = value.barang_id;
        kode_barang = value.kode_barang;
        nama_barang = value.nama_barang;
        harga = value.harga;
        jumlah = value.jumlah;
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


    // kode_barang 
    item_pembelian += '<td>';
    item_pembelian += '<div class="form-group">'
    item_pembelian += '<input style="font-size:14px" type="text" onclick="barang(' + urutan +
        ')" class="form-control kode_barang" readonly id="kode_barang-' +
        urutan +
        '" name="kode_barang[]" value="' + kode_barang + '" ';
    item_pembelian += '</div>';
    item_pembelian += '</td>';


    // nama_barang 
    item_pembelian += '<td>';
    item_pembelian += '<div class="form-group">'
    item_pembelian += '<input style="font-size:14px" type="text" onclick="barang(' + urutan +
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
        '" name="harga[]" onclick="barang(' + urutan +
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

    // satuan_id 
    item_pembelian += '<td style="width: 220px">';
    item_pembelian += '<div style="font-size:14px" class="form-group">';
    item_pembelian += '<select class="form-control select2bs4" id="satuan_id-' + key +
        '" name="satuan_id[]">';
    item_pembelian += '<option value="">Pilih Satuan..</option>';
    item_pembelian += '@foreach ($satuans as $satuan_id)';
    item_pembelian +=
        '<option value="{{ $satuan_id->id }}" {{ $satuan_id->id == ' + satuan_id + ' ? '
    selected ' : '
    ' }}>{{ $satuan_id->kode_satuan }}</option>';
    item_pembelian += '@endforeach';
    item_pembelian += '</select>';
    item_pembelian += '</div>';
    item_pembelian += '</td>';

    // total
    item_pembelian += '<td>';
    item_pembelian += '<div class="form-group">'
    item_pembelian += '<input style="text-align: right" onclick="barang(' + urutan +
        ')" type="text" class="form-control total" readonly id="total-' + urutan +
        '" name="total[]" value="' + total + '" readonly';
    item_pembelian += '</div>';
    item_pembelian += '</td>';

    item_pembelian += '<td style="width: 100px">';
    item_pembelian += '<button type="button" class="btn btn-primary btn-sm" onclick="barang(' + urutan + ')">';
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
</script>

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

$(document).on("input", ".harga, .jumlah", function() {
    var currentRow = $(this).closest('tr');

    // Ambil nilai harga dan hapus titik ribuan
    var hargaText = currentRow.find(".harga").val();
    var harga = parseFloat(hargaText.replace(/\./g, '')) || 0;

    var jumlah = parseFloat(currentRow.find(".jumlah").val()) || 0;
    var total = harga * jumlah;

    // Set hasil total dalam format rupiah
    currentRow.find(".total").val(formatRupiah1(total));
    grandTotal();
});


var activeSpecificationIndex = 0;

function barang(param) {
    var pelangganId = $('#pelanggan_id').val();

    if (!pelangganId) {
        Swal.fire({
            icon: 'warning',
            title: 'Pilih Pelanggan',
            text: 'Silakan pilih pelanggan terlebih dahulu sebelum memilih barang.',
        });
        return; // hentikan fungsi jika pelanggan belum dipilih
    }

    activeSpecificationIndex = param;
    $('#tableBarang').modal('show');
}


function getBarang(rowIndex) {
    var selectedRow = $('#tables tbody tr:eq(' + rowIndex + ')');
    var barang_id = selectedRow.data('barang_id');
    var kode_barang = selectedRow.data('kode_barang');
    var nama_barang = selectedRow.data('nama_barang');
    var harga = selectedRow.data('harga');

    function formatRupiah1(angka) {
        if (!angka) return '0';
        angka = parseFloat(angka.toString().replace(/\D/g, '')) || 0;
        return new Intl.NumberFormat('id-ID').format(angka);
    }

    // Update the form fields
    $('#barang_id-' + activeSpecificationIndex).val(barang_id);
    $('#kode_barang-' + activeSpecificationIndex).val(kode_barang);
    $('#nama_barang-' + activeSpecificationIndex).val(nama_barang);
    $('#harga-' + activeSpecificationIndex).val(formatRupiah1(harga));

    // Ambil jumlah (kalau ada)
    var jumlah = parseFloat($('#jumlah-' + activeSpecificationIndex).val()) || 0;

    // Hitung total dan masukkan ke field total
    var total = harga * jumlah;
    $('#total-' + activeSpecificationIndex).val(formatRupiah1(total));

    grandTotal()
    $('#tableBarang').modal('hide');
}

function grandTotal() {
    let totalSemua = 0;

    // Menjumlahkan semua nilai dari input dengan ID diawali 'total-'
    $("[id^='total-']").each(function() {
        let val = $(this).val().toString().replace(/\./g, '');
        let angka = parseFloat(val) || 0;
        totalSemua += angka;
    });

    // Ambil nilai dari nominal_pembayaran
    let pembayaranText = $('#nominal_pembayaran').val() || '0';
    let totalPembayaran = parseFloat(pembayaranText.toString().replace(/\./g, '')) || 0;

    // Hitung grand total
    let grandTotal = totalSemua - totalPembayaran;

    // Tampilkan hasil ke dalam input
    $('#total_harga').val(formatRupiahsss(totalSemua));
    $('#grand_total').val(formatRupiahsss(grandTotal));
}

// Jalankan grandTotal() setiap kali input nominal_pembayaran berubah
$(document).on('input', '#nominal_pembayaran', function() {
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
{{-- <script>
        $(document).ready(function() {
            // Tambahkan event listener pada tombol "Simpan"
            $('#btnSimpan').click(function() {
                // Sembunyikan tombol "Simpan" dan "Reset", serta tampilkan elemen loading
                $(this).hide();
                $('#btnReset')
                    .hide(); // Tambahkan id "btnReset" pada tombol "Reset"
                $('#loading').show();

                // Lakukan pengiriman formulir
                $('form').submit();
            });
        });
    </script> --}}
<script>
$(document).ready(function() {
    $('form').on('submit', function(e) {
        var pelangganName = $('#kode_pelanggan').val().trim();
        var barangValid = true;

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

        // Validasi untuk jumlah barang
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

        var NominalBayar = $('#nominal_pembayaran').val().trim();
        var barangValid = true;

        // Validasi pelanggan
        if (NominalBayar === "") {
            e.preventDefault();
            $('#error-nominal').text('Masukkan Nominal Pembayaran.');
            $('#nominal_pembayaran').addClass('is-invalid');
        } else {
            $('#error-nominal').text('');
            $('#nominal_pembayaran').removeClass('is-invalid');
        }


        // Jika ada input yang tidak valid
        if (!barangValid) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Data Barang Tidak Lengkap',
                text: 'Mohon lengkapi semua data barang sebelum menyimpan!',
                confirmButtonText: 'OK'
            });
        }

        // Jika semua valid, sembunyikan tombol & tampilkan loading
        if (pelangganName !== "" && barangValid) {
            $('#btnSimpan').hide();
            $('#btnReset').hide();
            $('#loading').show();
        }
    });
});
</script>


<!-- <script>
// Fungsi untuk menangani scan
function handleScan() {
    var scanValue = $('#scanInput').val();

    if (scanValue) {
        addPesanan(); // Menjalankan fungsi ketika ada scan

        // Masukkan hasil scan ke dalam form pencarian
        $('#searchInput').val(scanValue);
        filterTable(); // Jalankan filter pencarian otomatis

        $('#scanInput').val(''); // Kosongkan input setelah scan
    }
}

// Fungsi untuk memfilter tabel berdasarkan input pencarian
function filterTable() {
    var input, filter, table, tr, td, i, j, txtValue;
    input = document.getElementById("searchInput");
    filter = input.value.toUpperCase();
    table = document.getElementById("tables");
    tr = table.getElementsByTagName("tr");

    for (i = 0; i < tr.length; i++) {
        var displayRow = false;

        // Loop melalui kolom (td 1, 2, dan 3)
        for (j = 1; j <= 3; j++) {
            td = tr[i].getElementsByTagName("td")[j];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    displayRow = true;
                    break; // Keluar dari loop jika ditemukan kecocokan di kolom mana pun
                }
            }
        }

        // Tentukan gaya tampilan berdasarkan apakah kecocokan ditemukan di kolom mana pun
        tr[i].style.display = displayRow ? "" : "none";
    }
}

// Menambahkan event listener untuk input pencarian
document.getElementById("searchInput").addEventListener("input", filterTable);
</script> -->


@endsection