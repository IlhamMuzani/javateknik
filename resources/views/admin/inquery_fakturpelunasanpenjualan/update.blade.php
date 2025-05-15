@extends('layouts.app')

@section('title', 'Inquery Pelunasan Penjualan')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
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
            @if (session('erorrss'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5>
                        <i class="icon fas fa-ban"></i> Gagal!
                    </h5>
                    {{ session('erorrss') }}
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
            <form action="{{ url('admin/inquery-pelunasan-penjualan/' . $inquery->id) }}" method="POST"
                enctype="multipart/form-data" autocomplete="off">
                @csrf
                @method('put')
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Perbarui Pelunasan Penjualan</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="form-group" style="flex: 8;">
                            <div class="row">
                                {{-- <div class="mb-3 mt-4">
                                <button class="btn btn-primary btn-sm" type="button" onclick="ShowMemo(this.value)">
                                    <i class="fas fa-plus mr-2"></i> Pilih Pelanggan
                                </button>
                            </div> --}}
                                <div class="form-group" hidden>
                                    <label for="pelanggan_id">pelanggan Id</label>
                                    <input type="text" class="form-control" id="pelanggan_id" readonly
                                        name="pelanggan_id" placeholder=""
                                        value="{{ old('pelanggan_id', $inquery->pelanggan_id) }}">
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label style="font-size:14px" for="kode_pelanggan">Kode Pelanggan</label>
                                        <input onclick="showCategoryModalPelanggan(this.value)" style="font-size:14px"
                                            type="text" class="form-control" id="kode_pelanggan" readonly
                                            name="kode_pelanggan" placeholder=""
                                            value="{{ old('kode_pelanggan', $inquery->kode_pelanggan) }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label style="font-size:14px" class="form-label" for="nama_pelanggan">Nama
                                        Pelanggan</label>
                                    <div class="form-group d-flex">
                                        <input onclick="showCategoryModalPelanggan(this.value)" class="form-control"
                                            id="nama_pelanggan" name="nama_pelanggan" type="text" placeholder=""
                                            value="{{ old('nama_pelanggan', $inquery->nama_pelanggan) }}" readonly
                                            style="margin-right: 10px; font-size:14px" />
                                        <button class="btn btn-primary" type="button"
                                            onclick="showCategoryModalPelanggan(this.value)">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label style="font-size:14px" for="telp_pelanggan">No. Telp</label>
                                        <input onclick="showCategoryModalPelanggan(this.value)" style="font-size:14px"
                                            type="text" class="form-control" id="telp_pelanggan" readonly
                                            name="telp_pelanggan" placeholder=""
                                            value="{{ old('telp_pelanggan', $inquery->telp_pelanggan) }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label style="font-size:14px" for="alamat_pelanggan">Alamat</label>
                                        <input onclick="showCategoryModalPelanggan(this.value)" style="font-size:14px"
                                            type="text" class="form-control" id="alamat_pelanggan" readonly
                                            name="alamat_pelanggan" placeholder=""
                                            value="{{ old('alamat_pelanggan', $inquery->alamat_pelanggan) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label style="font-size:14px" for="keterangan">Catatan</label>
                            <div class="form-group">
                                <textarea style="font-size:14px" class="form-control" name="keterangan" placeholder="masukkan catatan">{{ old('keterangan', $inquery->keterangan) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Pelunasan Faktur Penjualan <span>
                            </span></h3>
                        <div class="float-right">
                            <button type="button" class="btn btn-primary btn-sm" onclick="addPesanan()">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="font-size:14px" class="text-center">No</th>
                                    <th style="font-size:14px">Kode Penjualan</th>
                                    <th style="font-size:14px">Tgl Penjualan</th>
                                    <th style="font-size:14px">Sub Total</th>
                                    <th style="font-size:14px; text-align:center">Opsi</th>
                                </tr>
                            </thead>
                            <tbody id="tabel-penjualan">
                                @foreach ($details as $detail)
                                    <tr id="penjualan-{{ $loop->index }}">
                                        <td style="width: 70px; font-size:14px" class="text-center" id="urutan">
                                            {{ $loop->index + 1 }}
                                        </td>
                                        <div class="form-group" hidden>
                                            <input type="text" class="form-control"
                                                id="nomor_seri-{{ $loop->index }}" name="detail_ids[]"
                                                value="{{ $detail['id'] }}">
                                        </div>
                                        <td hidden>
                                            <div class="form-group">
                                                <input type="text" class="form-control"
                                                    id="penjualan_id-{{ $loop->index }}" name="penjualan_id[]"
                                                    value="{{ $detail['penjualan_id'] }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input onclick="MemoEkspedisi({{ $loop->index }})"
                                                    style="font-size:14px" readonly type="text" class="form-control"
                                                    id="kode_penjualan-{{ $loop->index }}" name="kode_penjualan[]"
                                                    value="{{ $detail['kode_penjualan'] }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input onclick="MemoEkspedisi({{ $loop->index }})"
                                                    style="font-size:14px" readonly type="text" class="form-control"
                                                    id="tanggal_penjualan-{{ $loop->index }}" name="tanggal_penjualan[]"
                                                    value="{{ $detail['tanggal_penjualan'] }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input onclick="Return({{ $loop->index }})" style="font-size:14px"
                                                    type="text" readonly class="form-control"
                                                    id="total-{{ $loop->index }}" name="total[]"
                                                    oninput="updateTotalPembayaran()"
                                                    value="{{ number_format($detail['total'], 2, ',', '.') }}">
                                            </div>
                                        </td>
                                        <td style="width: 100px">
                                            <button type="button" class="btn btn-primary btn-sm"
                                                onclick="MemoEkspedisi({{ $loop->index }})">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                            <button style="margin-left:5px" type="button" class="btn btn-danger btn-sm"
                                                onclick="removeBan({{ $loop->index }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Rincian Pembayaran</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group" style="flex: 8;">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label style="font-size:14px" for="potongan">Potongan Penjualan</label>
                                                <input style="font-size:14px" type="text" class="form-control"
                                                    id="potongan" name="potongan" placeholder="masukkan potongan"
                                                    value="{{ old('potongan', $inquery->potongan) }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label style="font-size:14px" for="tambahan_pembayaran">Ongkos
                                                    Bongkar</label>
                                                <input style="font-size:14px" type="text" class="form-control"
                                                    id="tambahan_pembayaran" name="tambahan_pembayaran"
                                                    placeholder="masukkan ongkos bongkar"
                                                    value="{{ old('tambahan_pembayaran', $inquery->tambahan_pembayaran) }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label style="font-size: 14px" for="panjang">Kategori Pembayaran</label>
                                    <select style="font-size: 14px" class="form-control" id="kategori" name="kategori">
                                        <option value="">- Pilih -</option>
                                        <option value="Bilyet Giro"
                                            {{ old('kategori', $inquery->kategori) == 'Bilyet Giro' ? 'selected' : null }}>
                                            Bilyet Giro BG / Cek</option>
                                        <option value="Transfer"
                                            {{ old('kategori', $inquery->kategori) == 'Transfer' ? 'selected' : null }}>
                                            Transfer</option>
                                        <option value="Tunai"
                                            {{ old('kategori', $inquery->kategori) == 'Tunai' ? 'selected' : null }}>
                                            Tunai</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label style="font-size: 14px" id="bg" for="lebar">No. BG/Cek</label>
                                    <label style="font-size: 14px" id="trans" for="lebar">No. Transfer</label>
                                    <label style="font-size: 14px" id="tun" for="lebar">Tunai</label>
                                    <input style="font-size: 14px" type="text" class="form-control" id="nomor"
                                        name="nomor" placeholder="masukkan no"
                                        value="{{ old('nomor', $inquery->nomor) }}">
                                </div>
                                <div class="form-group">
                                    <label style="font-size: 14px" for="tinggi">Tanggal</label>
                                    <div class="input-group date" id="reservationdatetime">
                                        <input style="font-size: 14px" type="date" id="tanggal"
                                            name="tanggal_transfer" placeholder="d M Y sampai d M Y"
                                            data-options='{"mode":"range","dateFormat":"d M Y","disableMobile":true}'
                                            value="{{ old('tanggal_transfer', $inquery->tanggal_transfer) }}"
                                            class="form-control datetimepicker-input" data-target="#reservationdatetime">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label style="font-size: 14px" for="tinggi">Nominal</label>
                                    <input style="font-size: 14px" type="text" class="form-control" id="nominal"
                                        placeholder="masukkan nominal" name="nominal" name="nominal"
                                        value="{{ $inquery->nominal }}">
                                </div>
                            </div>
                            <div class="col-md-6" style="margin-left: 89px">
                                <div class="form-group">
                                    <label style="font-size: 14px" for="totalpenjualan">Sub Total</label>
                                    <input style="text-align: end; font-size: 14px" type="text" class="form-control"
                                        id="totalpembayaran" readonly name="totalpenjualan" placeholder=""
                                        value="{{ old('totalpenjualan', number_format($inquery->totalpenjualan, 2, ',', '.')) }}">

                                </div>
                                <div class="form-group">
                                    <label style="font-size: 14px" for="tinggi">Ongkos Bongkar</label>
                                    <input style="text-align: end; font-size: 14px" type="text" class="form-control"
                                        id="ongkosBongkar" readonly name="dp" placeholder=""
                                        value="{{ old('dp', number_format($inquery->dp, 2, ',', '.')) }}">

                                </div>
                                <div class="form-group">
                                    <label style="font-size: 14px" for="tinggi">Potongan</label>
                                    <input style="text-align: end; font-size: 14px" type="text" class="form-control"
                                        id="potonganselisih" readonly name="potonganselisih" placeholder=""
                                        value="{{ old('potonganselisih', number_format($inquery->potonganselisih, 2, ',', '.')) }}">
                                </div>
                                <hr style="border: 2px solid black;">
                                <div class="form-group">
                                    <label style="font-size: 14px" for="tinggi">Total Pembayaran</label>
                                    <input style="text-align: end; font-size: 14px" type="text" class="form-control"
                                        id="totalPembayaran" readonly name="totalpembayaran"
                                        value="{{ old('totalpembayaran', $inquery->totalpembayaran) }}" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label style="font-size: 14px" for="tinggi">Selisih Pembayaran</label>
                                    <input style="text-align: end; font-size: 14px" type="text" class="form-control"
                                        id="hasilselisih" readonly name="selisih"
                                        value="{{ old('selisih', number_format($inquery->selisih, 2, ',', '.')) }}"
                                        placeholder="">
                                </div>
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
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Kode Pelanggan</th>
                                    <th>Nama Pelanggan</th>
                                    <th>Alamat</th>
                                    <th>No. Telp</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pelanggans as $pelanggan)
                                    <tr
                                        onclick="getSelectedDataPelanggan('{{ $pelanggan->id }}', '{{ $pelanggan->kode_pelanggan }}', '{{ $pelanggan->nama_pelanggan }}', '{{ $pelanggan->alamat }}', '{{ $pelanggan->telp }}')">
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $pelanggan->kode_pelanggan }}</td>
                                        <td>{{ $pelanggan->nama_pelanggan }}</td>
                                        <td>{{ $pelanggan->alamat }}</td>
                                        <td>{{ $pelanggan->telp }}</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-primary btn-sm"
                                                onclick="getSelectedDataPelanggan('{{ $pelanggan->id }}', '{{ $pelanggan->kode_pelanggan }}', '{{ $pelanggan->nama_pelanggan }}', '{{ $pelanggan->alamat }}', '{{ $pelanggan->telp }}')">
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

        <div class="modal fade" id="tableBan" data-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Data Faktur Ekspedisi</h4>
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
                                    <th>Kode Faktur</th>
                                    <th>Tanggal</th>
                                    <th>Pelanggan</th>
                                    <th>Total</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($fakturs as $faktur)
                                    <tr onclick="getFaktur({{ $loop->index }})" data-id="{{ $faktur->id }}"
                                        data-kode_penjualan="{{ $faktur->kode_penjualan }}"
                                        data-tanggal_awal="{{ $faktur->tanggal_awal }}"
                                        data-grand_total="{{ $faktur->grand_total }}" data-param="{{ $loop->index }}">
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $faktur->kode_penjualan }}</td>
                                        <td>{{ $faktur->tanggal }}</td>
                                        <td>{{ $faktur->pelanggan->nama_pelanggan }}</td>
                                        {{-- <td>
                                            {{ $faktur->kode_penjualan }}
                                        </td> --}}
                                        <td>{{ number_format($faktur->grand_total, 2, ',', '.') }}</td>

                                        {{-- <td>{{ $faktur->detail_faktur->first()->memo_ekspedisi->kode_memo }}</td> --}}
                                        <td class="text-center">
                                            <button type="button" id="btnTambah" class="btn btn-primary btn-sm"
                                                onclick="getFaktur({{ $loop->index }})">
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

    <script>
        function showCategoryModalPelanggan(selectedCategory) {
            $('#tablePelanggan').modal('show');
        }

        function getSelectedDataPelanggan(Pelanggan_id, KodePelanggan, NamaPell, AlamatPel, Telpel) {
            // Set the values in the form fields
            document.getElementById('pelanggan_id').value = Pelanggan_id;
            document.getElementById('kode_pelanggan').value = KodePelanggan;
            document.getElementById('nama_pelanggan').value = NamaPell;
            document.getElementById('alamat_pelanggan').value = AlamatPel;
            document.getElementById('telp_pelanggan').value = Telpel;
            // Close the modal (if needed)
            $('#tablePelanggan').modal('hide');
        }
    </script>

    <script>
        var data_pembelian = @json(session('data_pembelians'));
        var jumlah_item = 1;

        if (data_pembelian != null) {
            jumlah_item = data_pembelian.length;
            $('#tabel-penjualan').empty();
            var urutan = 0;
            $.each(data_pembelian, function(key, value) {
                urutan = urutan + 1;
                itemPenjualan(urutan, key, value);
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
            jumlah_item = jumlah_item + 1;

            if (jumlah_item === 1) {
                $('#tabel-penjualan').empty();
            } else {
                // Find the last row and get its index to continue the numbering
                var lastRow = $('#tabel-penjualan tr:last');
                var lastRowIndex = lastRow.find('#urutan').text();
                jumlah_item = parseInt(lastRowIndex) + 1;
            }

            console.log('Current jumlah_item:', jumlah_item);
            itemPenjualan(jumlah_item, jumlah_item - 1);
            updateUrutan();
        }

        function removeBan(identifier, detailId) {
            var row = document.getElementById('penjualan-' + identifier);
            row.remove();

            $.ajax({
                url: "{{ url('admin/ban/') }}/" + detailId,
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

            updateUrutan();
            updateTotalPembayaran()
        }

        function itemPenjualan(identifier, key, value = null) {
            var penjualan_id = '';
            var kode_penjualan = '';
            var tanggal_penjualan = '';
            var total = '';

            if (value !== null) {
                penjualan_id = value.penjualan_id;
                kode_penjualan = value.kode_penjualan;
                tanggal_penjualan = value.tanggal_penjualan;
                total = value.total;
            }
            // urutan 
            var item_penjualan = '<tr id="penjualan-' + key + '">';
            item_penjualan += '<td style="width: 70px; font-size:14px" class="text-center" id="urutan">' + key + '</td>';

            // penjualan_id 
            item_penjualan += '<td hidden>';
            item_penjualan += '<div class="form-group">'
            item_penjualan += '<input type="text" class="form-control" id="penjualan_id-' + key +
                '" name="penjualan_id[]" value="' +
                penjualan_id +
                '" ';
            item_penjualan += '</div>';
            item_penjualan += '</td>';

            // kode_penjualan
            item_penjualan += '<td onclick="MemoEkspedisi(' + key +
                ')">';
            item_penjualan += '<div class="form-group">'
            item_penjualan +=
                '<input type="text" class="form-control" style="font-size:14px" readonly id="kode_penjualan-' +
                key + '" name="kode_penjualan[]" value="' +
                kode_penjualan +
                '" ';
            item_penjualan += '</div>';
            item_penjualan += '</td>';

            // tanggal_penjualan
            item_penjualan += '<td onclick="MemoEkspedisi(' + key +
                ')">';
            item_penjualan += '<div class="form-group">'
            item_penjualan +=
                '<input type="text" class="form-control" style="font-size:14px" readonly id="tanggal_penjualan-' + key +
                '" name="tanggal_penjualan[]" value="' +
                tanggal_penjualan +
                '" ';
            item_penjualan += '</div>';
            item_penjualan += '</td>';

            // total 
            item_penjualan += '<td>';
            item_penjualan += '<div class="form-group">'
            item_penjualan +=
                '<input type="text" class="form-control" readonly style="font-size:14px" id="total-' +
                key +
                '" name="total[]" value="' + total + '" ';
            item_penjualan += '</div>';
            item_penjualan += '</td>';

            item_penjualan += '<td style="width: 100px">';
            item_penjualan += '<button type="button" class="btn btn-primary btn-sm" onclick="MemoEkspedisi(' + key +
                ')">';
            item_penjualan += '<i class="fas fa-plus"></i>';
            item_penjualan += '</button>';
            item_penjualan +=
                '<button style="margin-left:10px" type="button" class="btn btn-danger btn-sm" onclick="removeBan(' +
                key + ')">';
            item_penjualan += '<i class="fas fa-trash"></i>';
            item_penjualan += '</button>';
            item_penjualan += '</td>';
            item_penjualan += '</tr>';

            $('#tabel-penjualan').append(item_penjualan);
        }
    </script>


    <script>
        var activeSpecificationIndex = 0;

        function MemoEkspedisi(param) {
            activeSpecificationIndex = param;
            // Show the modal and filter rows if necessary
            $('#tableBan').modal('show');
        }

        function getFaktur(rowIndex) {
            var selectedRow = $('#tables tbody tr:eq(' + rowIndex + ')');
            var penjualan_id = selectedRow.data('id');
            var kode_penjualan = selectedRow.data('kode_penjualan');
            var tanggal_awal = selectedRow.data('tanggal_awal');
            var grand_total = selectedRow.data('grand_total');

            $('#penjualan_id-' + activeSpecificationIndex).val(penjualan_id);
            $('#kode_penjualan-' + activeSpecificationIndex).val(kode_penjualan);
            $('#tanggal_penjualan-' + activeSpecificationIndex).val(tanggal_awal);
            $('#total-' + activeSpecificationIndex).val(grand_total);

            updateTotalPembayaran();
            Hasil(); // Panggil Hasil dengan tanda kurung

            $('#tableBan').modal('hide');
        }

        function updateTotalPembayaran() {
            var grandTotal = 0;

            // Iterate through all input elements with IDs starting with 'total-'
            $('input[id^="total-"]').each(function() {
                // Remove dots and replace comma with dot, then parse as float
                var nilaiTotal = parseFloat($(this).val().replace(/\./g, '').replace(',', '.')) || 0;
                grandTotal += nilaiTotal;
            });

            // Format grandTotal as currency in Indonesian Rupiah
            var formattedGrandTotal = grandTotal.toLocaleString('id-ID');
            console.log(formattedGrandTotal);
            // Set the formatted grandTotal to the target element
            $('#totalpembayaran').val(formattedGrandTotal);
        }
    </script>


    <script>
        function toggleLabels() {
            var kategori = document.getElementById('kategori');
            var bgLabel = document.getElementById('bg');
            var transLabel = document.getElementById('trans');
            var tunLabel = document.getElementById('tun');
            var Nomor = document.getElementById('nomor');

            if (kategori.value === 'Bilyet Giro') {
                bgLabel.style.display = 'block';
                transLabel.style.display = 'none';
                tunLabel.style.display = 'none';
                Nomor.style.display = 'block';
            } else if (kategori.value === 'Transfer') {
                bgLabel.style.display = 'none';
                transLabel.style.display = 'block';
                tunLabel.style.display = 'none';
                Nomor.style.display = 'block';
            } else if (kategori.value === 'Tunai') {
                bgLabel.style.display = 'none';
                transLabel.style.display = 'none';
                tunLabel.style.display = 'none';
                Nomor.style.display = 'none';
            }
        }
        toggleLabels();
        document.getElementById('kategori').addEventListener('change', toggleLabels);

        PenyamaanDP()

        function PenyamaanDP() {
            var potonganInput = document.getElementById('potongan');
            var potonganselisihInput = document.getElementById('potonganselisih');

            function formatRupiah(angka) {
                var reverse = angka.toString().split('').reverse().join(''),
                    ribuan = reverse.match(/\d{1,3}/g);
                ribuan = ribuan.join('.').split('').reverse().join('');
                return ribuan;
            }

            // Set nilai default untuk potonganselisih saat halaman dimuat
            if (potonganInput.value === '') {
                potonganselisihInput.value = '0';
            }

            // Mendengarkan perubahan pada input "potongan"
            potonganInput.addEventListener('input', function() {
                var potonganValue = this.value;

                // Jika input potongan kosong, atur nilai potonganselisih ke 0
                if (potonganValue === '') {
                    potonganselisihInput.value = '0';
                } else {
                    var formattedValue = formatRupiah(potonganValue);
                    potonganselisihInput.value = formattedValue;
                }
            });
            var oldPotongan = "{{ old('potongan') }}";
            if (oldPotongan !== '') {
                potonganInput.value = oldPotongan;
                var formattedOldValue = formatRupiah(oldPotongan);
                potonganselisihInput.value = formattedOldValue;
            }
        }
        document.addEventListener('DOMContentLoaded', PenyamaanDP);


        function OngkosBongkar() {
            var dpInput = document.getElementById('ongkosBongkar');
            var ongkosBongkarInput = document.getElementById('tambahan_pembayaran');

            function formatRupiah(angka) {
                var reverse = angka.toString().split('').reverse().join(''),
                    ribuan = reverse.match(/\d{1,3}/g);
                ribuan = ribuan.join('.').split('').reverse().join('');
                return ribuan;
            }

            // Set nilai default untuk ongkosBongkar saat halaman dimuat
            if (ongkosBongkarInput.value === '') {
                dpInput.value = '0';
            }

            // Mendengarkan perubahan pada input "tambahan_pembayaran"
            ongkosBongkarInput.addEventListener('input', function() {
                var potonganValue = this.value;

                // Jika input tambahan_pembayaran kosong, atur nilai ongkosBongkar ke 0
                if (potonganValue === '') {
                    dpInput.value = '0';
                } else {
                    var formattedValue = formatRupiah(potonganValue);
                    dpInput.value = formattedValue;
                }
            });
            var oldOngkosBongkar = "{{ old('tambahan_pembayaran') }}";
            if (oldOngkosBongkar !== '') {
                ongkosBongkarInput.value = oldOngkosBongkar;
                var formattedOldValue = formatRupiah(oldOngkosBongkar);
                dpInput.value = formattedOldValue;
            }
        }
        document.addEventListener('DOMContentLoaded', OngkosBongkar);


        PenyamaanSelisih()

        function PenyamaanSelisih() {
            document.getElementById('hasilselisih').value = '0';
            document.getElementById('nominal').addEventListener('input', function() {
                var potonganValue = this.value;
                if (potonganValue === '') {
                    document.getElementById('hasilselisih').value = '0'; // Set to 0 when 'potongan' is empty
                } else {
                    var formattedValue = formatRupiah(potonganValue);
                    document.getElementById('hasilselisih').value = formattedValue;
                }
            });
        }

        Potongan()

        function Potongan() {
            document.getElementById('potongan').addEventListener('input', hitungSelisih);

            document.getElementById('tambahan_pembayaran').addEventListener('input', hitungSelisih);
            // Panggil fungsi hitungSelisih saat halaman dimuat (untuk menginisialisasi nilai selisih)
            window.addEventListener('load', hitungSelisih);
        }

        // function hapusTitik(string) {
        //     return string.replace(/\./g, '');
        // }

        function hapusTitik(string) {
            // Menghapus titik dan mengganti koma dengan titik
            return string.replace(/\./g, '').replace(',', '.');
        }


        function formatRupiah(number) {
            var formatted = new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(number);
            return '' + formatted;
        }

        // function formatRupiah(angka) {
        //     var reverse = angka.toString().split('').reverse().join('');
        //     var ribuan = reverse.match(/\d{1,3}/g);
        //     var formatted = ribuan.join('.').split('').reverse().join('');
        //     return 'Rp ' + formatted;
        // }


        hitungSelisih()

        function hitungSelisih() {

            // Dapatkan nilai dari input "Total Pembayaran" dan hapus titik
            var totalPembayaranInput = document.getElementById('totalpembayaran');
            var totalPembayaranValue = totalPembayaranInput.value;
            var totalPembayaran = parseFloat(hapusTitik(totalPembayaranValue)) || 0;


            var DpInput = document.getElementById('ongkosBongkar');
            var DpPembayaranValue = DpInput.value;
            var DpPembayaran = parseFloat(hapusTitik(DpPembayaranValue)) || 0;

            // Dapatkan nilai dari input "Nominal" dan hapus titik
            var nominalInput = document.getElementById('potongan');
            var nominalValue = nominalInput.value;
            var nominal = parseFloat(hapusTitik(nominalValue)) || 0;

            // Dapatkan nilai dari input "Nominal" dan hapus titik
            var OngkosInput = document.getElementById('tambahan_pembayaran');
            var OngkosValue = OngkosInput.value;
            var Ongkos = parseFloat(hapusTitik(OngkosValue)) || 0;

            // Hitung selisih
            var selisih = totalPembayaran + Ongkos - nominal;
            // console.log(selisih);

            // Tampilkan hasil selisih dalam format mata uang Rupiah dengan tanda negatif
            var hasilselisih = document.getElementById('totalPembayaran');

            // Tambahkan tanda negatif jika selisih negatif
            if (selisih < 0) {
                hasilselisih.value = '-' + formatRupiah(selisih);
            } else {
                hasilselisih.value = ' ' + formatRupiah(selisih);
            }

            HitungSelisihHasil();
        }

        function updateSubTotals() {
            // Ambil nilai Total dan pastikan sudah berupa angka
            var Total = parseFloat($('#totalpembayaran').val().replace(/\./g, '')) || 0;
            console.log(Total);

            // Ambil nilai OngkosBongkar dan pastikan sudah berupa angka
            var OngkosBongkar = parseFloat(parseCurrency($('#ongkosBongkar').val().replace(/\./g, ''))) || 0;
            console.log(OngkosBongkar);

            // Ambil nilai HargaTambahan dan pastikan sudah berupa angka
            var HargaTambahan = parseFloat(parseCurrency($('#potonganselisih').val().replace(/\./g, ''))) || 0;
            console.log(HargaTambahan);

            // Hitung Subtotal
            var Subtotal = Total + OngkosBongkar - HargaTambahan;
            console.log(Subtotal);

            // Menetapkan nilai ke input sub_total
            $('#totalPembayaran').val(formatRupiah(Subtotal));
        }

        function parseCurrency(value) {
            return parseFloat(value.replace(/[^\d.-]/g, '')) || 0;
        }

        Hasil()

        function Hasil() {
            function hapusTitik(string) {
                return string.replace(/\./g, '');
            }

            // Fungsi untuk mengubah angka menjadi format mata uang Rupiah
            function formatRupiah(angka) {
                var reverse = angka.toString().split('').reverse().join('');
                var ribuan = reverse.match(/\d{1,3}/g);
                var formatted = ribuan.join('.').split('').reverse().join('');
                return '' + formatted;
            }
            // Fungsi untuk menghitung selisih dan menampilkannya
            hitungSelisih();
            // Panggil fungsi hitungSelisih saat input "Nominal" berubah
            document.getElementById('nominal').addEventListener('input', hitungSelisih);

            // Panggil fungsi hitungSelisih saat halaman dimuat (untuk menginisialisasi nilai selisih)
            window.addEventListener('load', hitungSelisih);
        }

        function HitungSelisihHasil() {
            function formatRupiah(number) {
                var formatted = new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).format(number);
                return '' + formatted;
            }



            function formatRupiahToNumber(rupiah) {
                // Hapus karakter non-numeric kecuali koma
                var numericString = rupiah.replace(/[^0-9,]/g, '');

                // Ganti koma dengan titik jika diperlukan
                numericString = numericString.replace(',', '.');

                // Konversi string ke tipe data number
                var numericValue = parseFloat(numericString);
                return numericValue;
            }

            // Dapatkan nilai dari input "Total Pembayaran" dan konversikan ke tipe data number
            var totalPembayaranInput = document.getElementById('totalPembayaran');
            var totalPembayaranValue = totalPembayaranInput.value;
            var totalPembayaran = formatRupiahToNumber(totalPembayaranValue);

            // Dapatkan nilai dari input "Nominal" dan hapus titik
            var nominalInput = document.getElementById('nominal');
            var nominalValue = nominalInput.value;
            var nominal = parseFloat(hapusTitik(nominalValue)) || 0;

            // console.log(totalPembayaran);

            // Hitung selisih
            var selisih = totalPembayaran - nominal;

            console.log(selisih);

            // Tampilkan hasil selisih dalam format mata uang Rupiah dengan tanda negatif
            var hasilselisih = document.getElementById('hasilselisih');

            // Tambahkan tanda negatif jika selisih negatif
            if (selisih < 0) {
                hasilselisih.value = formatRupiahss(selisih);
            } else {
                hasilselisih.value = ' -' + formatRupiahss(selisih);
            }

            function formatRupiahss(number) {
                var formatted = new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).format(number);
                return '' + formatted;
            }

        }

        // Panggil fungsi HitungSelisihHasil saat input "Nominal" berubah
        document.getElementById('nominal').addEventListener('input', HitungSelisihHasil);

        // Panggil fungsi HitungSelisihHasil saat halaman dimuat (untuk menginisialisasi nilai selisih)
        window.addEventListener('load', HitungSelisihHasil);
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



@endsection
