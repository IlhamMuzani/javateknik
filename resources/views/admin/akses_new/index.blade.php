@extends('layouts.app')

@section('title', 'Data Akses')

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
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Data Akses</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Data Akses</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
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
                    <h3 class="card-title">Data Akses</h3>
                    <div class="float-right">
                        {{-- @if (auth()->user()->id == 1)
                            <a href="{{ url('admin/add-akses') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Tambah
                            </a>
                        @endif --}}
                    </div>
                </div>

                <!-- /.card-header -->
                <div class="card-body">
                    <div class="table-responsive" style="overflow-x: auto;">
                        <table id="datatables66" class="table table-bordered table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Kode User</th>
                                    <th>Nama</th>
                                    <th class="text-center" width="60">Akses</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($aksess as $akses)
                                    <tr class="{{ $loop->iteration % 2 == 0 ? 'bg-light' : '' }}">
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $akses->kode_user }}</td>
                                        <td>{{ $akses->karyawan->nama_lengkap }}</td>
                                        <td class="text-center">
                                            @if (auth()->user()->menufiturs()->where('nama', 'Hak Akses')->wherePivot('can_update', 1)->exists())
                                                <a href="{{ url('admin/akses-new/' . $akses->id . '/edit') }}"
                                                    class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
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
            }

            tanggalAkhir.value = "";
            var today = new Date().toISOString().split('T')[0];
            tanggalAkhir.value = today;
            tanggalAkhir.setAttribute('min', this.value);
        });

        var form = document.getElementById('form-action');

        function cari() {
            form.action = "{{ url('admin/inquery_memotambahan') }}";
            form.submit();
        }
    </script>

    <script>
        $(document).ready(function() {
            // Detect the change event on the 'status' dropdown
            $('#statusx').on('change', function() {
                // Get the selected value
                var selectedValue = $(this).val();

                // Check the selected value and redirect accordingly
                switch (selectedValue) {
                    case 'staff':
                        window.location.href = "{{ url('admin/akses') }}";
                        break;
                    case 'driver':
                        window.location.href = "{{ url('admin/hakaksesdriver') }}";
                        break;
                    default:
                        // Handle other cases or do nothing
                        break;
                }
            });
        });
    </script>

@endsection
