@extends('layouts.app')

@section('title', 'Laporan Faktur Pelunasan Pembelian')

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
        {{-- <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Laporan Faktur Pelunasan Pembelian</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Laporan Faktur Pelunasan Pembelian</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid --> --}}
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content" style="display: none;" id="mainContentSection">
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
                    <h3 class="card-title">Data Laporan Faktur Pelunasan Pembelian</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form method="GET" id="form-action">
                        <div class="row">
                            <div class="col-md-2 mb-3">
                                <label for="created_at">Kategori</label>
                                <select class="custom-select form-control" id="status" name="status">
                                    <option value="">- Pilih Laporan -</option>
                                    <option value="perfaktur" selected>Laporan Faktur</option>
                                    <option value="global">Laporan Global</option>
                                    {{-- <option value="akun">Laporan Kas Keluar Group by Akun</option>
                                    <option value="memo_tambahan">Saldo Kas</option> --}}
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="created_at">Tanggal Awal</label>
                                <input class="form-control" id="created_at" name="created_at" type="date"
                                    value="{{ Request::get('created_at') }}" max="{{ date('Y-m-d') }}" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="tanggal_akhir">Tanggal Akhir</label>
                                <input class="form-control" id="tanggal_akhir" name="tanggal_akhir" type="date"
                                    value="{{ Request::get('tanggal_akhir') }}" max="{{ date('Y-m-d') }}" />
                            </div>
                            <div class="col-md-2 mb-3">
                                @if (auth()->user()->menufiturs()->where('nama', 'Laporan Pelunasan Pembelian')->wherePivot('can_search', 1)->exists())
                                    <button type="button" class="btn btn-outline-primary btn-block" onclick="cari()">
                                        <i class="fas fa-search"></i> Cari
                                    </button>
                                @endif
                                @if (auth()->user()->menufiturs()->where('nama', 'Laporan Pelunasan Pembelian')->wherePivot('can_print', 1)->exists())
                                    <button type="button" class="btn btn-primary btn-block" onclick="printReport()"
                                        target="_blank">
                                        <i class="fas fa-print"></i> Cetak
                                    </button>
                                @endif
                            </div>
                        </div>
                    </form>
                    <table id="datatables66" class="table table-bordered table-striped table-hover" style="font-size: 13px">
                        <thead class="thead-dark">
                            <tr>
                                <th class="text-center">No</th>
                                <th>No Faktur</th>
                                <th>Tanggal</th>
                                <th>Supplier</th>
                                {{-- <th>PPH</th> --}}
                                <th style="text-align: end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($inquery as $fakturpelunasan)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>
                                        @if ($fakturpelunasan->pembelian)
                                            {{ $fakturpelunasan->pembelian->kode_pembelian }}
                                        @else
                                            tidak ada
                                        @endif
                                    </td>
                                    <td>
                                        @if ($fakturpelunasan->pembelian)
                                            {{ $fakturpelunasan->pembelian->tanggal_awal }}
                                        @else
                                            tidak ada
                                        @endif
                                    </td>
                                    <td>
                                        @if ($fakturpelunasan->pembelian)
                                            {{ $fakturpelunasan->pembelian->supplier->nama_supp }}
                                        @else
                                            tidak ada
                                        @endif
                                    </td>
                                    {{-- <td style="text-align: end">
                                        {{ number_format($fakturpelunasan->pph, 0, ',', '.') }}
                                    </td> --}}
                                    <td style="text-align: end">
                                        {{ number_format($fakturpelunasan->total, 0, ',', '.') }}
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
        var tanggalAwal = document.getElementById('created_at');
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
            form.action = "{{ url('admin/laporan-pelunasan-pembelian') }}";
            form.submit();
        }

        function printReport() {
            var startDate = tanggalAwal.value;
            var endDate = tanggalAkhir.value;

            if (startDate && endDate) {
                form.action = "{{ url('admin/print-pelunasan-pembelian') }}" + "?start_date=" + startDate + "&end_date=" +
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
            $('#status').on('change', function() {
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
