@extends('layouts.app')

@section('title', 'Data Karyawan')

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
            
        </div>
    </div>
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
                    <h3 class="card-title">Data Karyawan</h3>
                    <div class="float-right">
                        @if (auth()->user()->menufiturs()->where('nama', 'Data Karyawan')->wherePivot('can_create', 1)->exists())
                            <a href="{{ url('admin/karyawan/create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Tambah
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <table id="datatables66" class="table table-bordered table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th class="text-center">No</th>
                                <th>Kode Karyawan</th>
                                <th>Nama</th>
                                <th>Departemen</th>
                                <th>Nama Bank</th>
                                <th>Nomor Rek</th>
                                <th>Nama Penerima</th>
                                <th class="text-center">Qr Code</th>
                                <th class="text-center" width="100">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($karyawans as $karyawan)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $karyawan->kode_karyawan }}</td>
                                    <td>{{ $karyawan->nama_lengkap }}</td>
                                    <td>{{ $karyawan->departemen->nama }}</td>
                                    <td>{{ $karyawan->nama_bank }}</td>
                                    <td>{{ $karyawan->norek }}</td>
                                    <td>{{ $karyawan->atas_nama }}</td>
                                    <td data-toggle="modal" data-target="#modal-qrcode-{{ $karyawan->id }}"
                                        style="text-align: center;">
                                        <div style="display: inline-block;">
                                            {!! DNS2D::getBarcodeHTML("$karyawan->qrcode_karyawan", 'QRCODE', 2, 2) !!}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if (auth()->user()->menufiturs()->where('nama', 'Data Karyawan')->wherePivot('can_show', 1)->exists())
                                            <a href="{{ url('admin/karyawan/' . $karyawan->id) }}"
                                                class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endif
                                        @if (auth()->user()->menufiturs()->where('nama', 'Data Karyawan')->wherePivot('can_update', 1)->exists())
                                            <a href="{{ url('admin/karyawan/' . $karyawan->id . '/edit') }}"
                                                class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                        @if (auth()->user()->menufiturs()->where('nama', 'Data Karyawan')->wherePivot('can_delete', 1)->exists())
                                            <button type="submit" class="btn btn-danger btn-sm" data-toggle="modal"
                                                data-target="#modal-hapus-{{ $karyawan->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                <div class="modal fade" id="modal-hapus-{{ $karyawan->id }}">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Hapus Karyawan</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Yakin hapus karyawan <strong>{{ $karyawan->nama }}</strong>?</p>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-default"
                                                    data-dismiss="modal">Batal</button>
                                                <form action="{{ url('admin/karyawan/' . $karyawan->id) }}" method="POST">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="modal-qrcode-{{ $karyawan->id }}">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Gambar QR Code</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div style="text-align: center;">
                                                    <p style="font-size:20px; font-weight: bold;">
                                                        {{ $karyawan->kode_karyawan }}</p>
                                                    <div style="display: inline-block;">
                                                        {!! DNS2D::getBarcodeHTML("$karyawan->qrcode_karyawan", 'QRCODE', 15, 15) !!}
                                                    </div>
                                                    <p style="font-size:20px; font-weight: bold;">
                                                        {{ $karyawan->nama_lengkap }}</p>
                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-default"
                                                        data-dismiss="modal">Batal</button>
                                                    <a href="{{ url('admin/karyawan/cetak-pdf/' . $karyawan->id) }}"
                                                        class="btn btn-primary btn-sm">
                                                        <i class=""></i> Cetak
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
