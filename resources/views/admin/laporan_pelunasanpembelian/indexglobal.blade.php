@extends('layouts.app')

@section('title', 'Laporan Pelunasan Faktur Pembelian Global')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Laporan Pelunasan Faktur Pembelian Global</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Laporan Pelunasan Faktur Pembelian Global</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5>
                        <i class="icon fas fa-check"></i> Berhasil!
                    </h5>
                    {{ session('success') }}
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Laporan Pelunasan Faktur Pembelian Global</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form method="GET" id="form-action">
                        <div class="row">
                            <div class="col-md-2 mb-3">
                                <label for="created_at">Kategori</label>
                                <select class="custom-select form-control" id="cases" name="cases">
                                    <option value="">- Pilih Laporan -</option>
                                    <option value="perfaktur">Laporan Faktur</option>
                                    <option value="global" selected>Laporan Global</option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="created_at">Status</label>
                                <select class="custom-select form-control" id="status" name="status">
                                    <option value="">- Semua Status -</option>
                                    <option value="unpost" {{ Request::get('status') == 'unpost' ? 'selected' : '' }}>
                                        Piutang
                                    </option>
                                    <option value="posting" {{ Request::get('status') == 'posting' ? 'selected' : '' }}>
                                        Lunas</option>
                                </select>

                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="tanggal_awal">Tanggal Awal</label>
                                <input class="form-control" id="tanggal_awal" name="tanggal_awal" type="date"
                                    value="{{ Request::get('tanggal_awal') }}" max="{{ date('Y-m-d') }}" />
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="tanggal_akhir">Tanggal Akhir</label>
                                <input class="form-control" id="tanggal_akhir" name="tanggal_akhir" type="date"
                                    value="{{ Request::get('tanggal_akhir') }}" max="{{ date('Y-m-d') }}" />
                            </div>
                            <div class="col-md-2 mb-3">
                                <button type="button" class="btn btn-outline-primary btn-block" onclick="cari()">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                                <button type="button" class="btn btn-primary btn-block" onclick="printReport()"
                                    target="_blank">
                                    <i class="fas fa-print"></i> Cetak
                                </button>
                            </div>
                        </div>
                    </form>
                    <table id="datatables66" class="table table-bordered table-striped table-hover" style="font-size: 13px">
                        <thead class="thead-dark">
                            <tr>
                                <th class="text-center">No</th>
                                <th>No Faktur</th>
                                <th>Tanggal</th>
                                <th>Admin</th>
                                <th>Pelanggan</th>
                                <th>Potongan</th>
                                <th>Tambahan</th>
                                {{-- <th>PPH</th> --}}
                                <th style="text-align: end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($inquery as $fakturpelunasan)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $fakturpelunasan->kode_pelunasan }}</td>
                                    <td>{{ $fakturpelunasan->tanggal_awal }}</td>
                                    <td>
                                        {{ $fakturpelunasan->user->karyawan->nama_lengkap }}
                                    </td>
                                    <td>
                                        {{ $fakturpelunasan->nama_supplier }}
                                    </td>
                                    <td style="text-align: end">
                                        {{ number_format($fakturpelunasan->potongan, 0, ',', '.') }}
                                    </td>
                                    <td style="text-align: end">
                                        {{ number_format($fakturpelunasan->ongkos_bongkar, 0, ',', '.') }}
                                    </td>
                                    <td style="text-align: end">
                                        {{ number_format($fakturpelunasan->totalpembayaran, 0, ',', '.') }}
                                    </td>
                                    </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </section>
    <!-- /.card -->
    <script>
        var tanggalAwal = document.getElementById('tanggal_awal');
        var tanggalAkhir = document.getElementById('tanggal_akhir');
        if (tanggalAwal.value == "") {
            tanggalAkhir.readOnly = true;
        }
        tanggalAwal.addEventListener('change', function() {
            if (this.value == "") {
                tanggalAkhir.readOnly = true;
            } else {
                tanggalAkhir.readOnly = false;
            };
            tanggalAkhir.value = "";
            var today = new Date().toISOString().split('T')[0];
            tanggalAkhir.value = today;
            tanggalAkhir.setAttribute('min', this.value);
        });
        var form = document.getElementById('form-action')

        function cari() {
            form.action = "{{ url('admin/laporan-pelunasan-pembelian-global') }}";
            form.submit();
        }

        function printReport() {
            var startDate = tanggalAwal.value;
            var endDate = tanggalAkhir.value;

            if (startDate && endDate) {
                form.action = "{{ url('admin/print-pelunasan-pembelian-global') }}" + "?start_date=" + startDate +
                    "&end_date=" +
                    endDate;
                form.submit();
            } else {
                alert("Silakan isi kedua tanggal sebelum mencetak.");
            }
        }
    </script>

    <script>
        $(document).ready(function() {
            // Detect the change event on the 'status' dropdown
            $('#cases').on('change', function() {
                // Get the selected value
                var selectedValue = $(this).val();

                // Check the selected value and redirect accordingly
                switch (selectedValue) {
                    case 'perfaktur':
                        window.location.href = "{{ url('admin/laporan-pelunasan-pembelian') }}";
                        break;
                    case 'global':
                        window.location.href = "{{ url('admin/laporan-pelunasan-pembelian-global') }}";
                        break;
                    default:
                        break;
                }
            });
        });
    </script>

@endsection
