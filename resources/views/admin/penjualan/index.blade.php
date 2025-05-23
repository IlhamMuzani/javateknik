@extends('layouts.app')

@section('title', 'Penjualan')

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
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5>
                        <i class="icon fas fa-ban"></i> Gagal!
                    </h5>
                    {{ session('error') }}
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Penjualan</h3>
                    <div class="float-right">
                        @if (auth()->user()->menufiturs()->where('nama', 'Penjualan')->wherePivot('can_create', 1)->exists())
                            <a href="{{ url('admin/penjualan/create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Tambah
                            </a>
                        @endif
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="table-responsive" style="overflow-x: auto;">
                        <table id="datatables66" class="table table-bordered table-striped table-hover"
                            style="font-size: 13px">
                            <thead class="thead-dark">
                                <tr>
                                    <th> <input type="checkbox" name="" id="select_all_ids"></th>
                                    <th class="text-center">No</th>
                                    <th>No. Penjualan</th>
                                    <th>Tanggal</th>
                                    <th>Nama Pelanggan</th>
                                    <th>Total</th>
                                    <th class="text-center" width="40">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($inquery as $item)
                                    <tr class="dropdown"{{ $item->id }}>
                                        <td><input type="checkbox" name="selectedIds[]" class="checkbox_ids"
                                                value="{{ $item->id }}">
                                        </td>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $item->kode_penjualan }}</td>
                                        <td>{{ $item->tanggal_awal }}</td>
                                        <td>
                                            @if ($item->pelanggan)
                                                {{ $item->pelanggan->nama_pelanggan }}
                                            @else
                                                tidak ada
                                            @endif
                                        </td>
                                        <td>{{ number_format($item->grand_total, 2, ',', '.') }}</td>
                                        <td class="text-center">
                                            @if ($item->status == 'posting')
                                                <button type="button" class="btn btn-success btn-sm">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            @endif
                                            @if ($item->status == 'selesai')
                                                <img src="{{ asset('storage/uploads/indikator/faktur.png') }}"
                                                    height="40" width="40" alt="document">
                                            @endif
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                @if ($item->status == 'unpost')
                                                    @if (auth()->user()->menufiturs()->where('nama', 'Penjualan')->wherePivot('can_posting', 1)->exists())
                                                        <a class="dropdown-item posting-btn"
                                                            data-memo-id="{{ $item->id }}">Posting</a>
                                                    @endif
                                                    @if (auth()->user()->menufiturs()->where('nama', 'Penjualan')->wherePivot('can_update', 1)->exists())
                                                        <a class="dropdown-item"
                                                            href="{{ url('admin/inquery-penjualan/' . $item->id . '/edit') }}">Update</a>
                                                    @endif
                                                    @if (auth()->user()->menufiturs()->where('nama', 'Penjualan')->wherePivot('can_show', 1)->exists())
                                                        <a class="dropdown-item"
                                                            href="{{ url('admin/penjualan/' . $item->id) }}">Show</a>
                                                    @endif
                                                    @if (auth()->user()->menufiturs()->where('nama', 'Penjualan')->wherePivot('can_delete', 1)->exists())
                                                        <form style="margin-top:5px" method="GET"
                                                            action="{{ route('hapuspenjualan', ['id' => $item->id]) }}">
                                                            <button type="submit"
                                                                class="dropdown-item btn btn-outline-danger btn-block mt-2">
                                                                </i> Delete
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endif
                                                @if ($item->status == 'posting')
                                                    @if (auth()->user()->menufiturs()->where('nama', 'Penjualan')->wherePivot('can_unpost', 1)->exists())
                                                        <a class="dropdown-item unpost-btn"
                                                            data-memo-id="{{ $item->id }}">Unpost</a>
                                                        <a class="dropdown-item"
                                                            href="{{ url('admin/penjualan/' . $item->id) }}">Show</a>
                                                    @endif
                                                @endif
                                                @if ($item->status == 'selesai')
                                                    @if (auth()->user()->menufiturs()->where('nama', 'Penjualan')->wherePivot('can_show', 1)->exists())
                                                        <a class="dropdown-item"
                                                            href="{{ url('admin/penjualan/' . $item->id) }}">Show</a>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Modal Loading -->
                    <div class="modal fade" id="modal-loading" tabindex="-1" role="dialog"
                        aria-labelledby="modal-loading-label" aria-hidden="true" data-backdrop="static">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-body text-center">
                                    <i class="fas fa-spinner fa-spin fa-3x text-primary"></i>
                                    <h4 class="mt-2">Sedang Menyimpan...</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Konfirmasi Hapus -->
                <div class="modal fade" id="modal-confirm-delete" tabindex="-1" role="dialog"
                    aria-labelledby="modal-confirm-delete-label" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modal-confirm-delete-label">Konfirmasi Hapus</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                Apakah Anda yakin ingin menghapus item yang dipilih?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="button" class="btn btn-danger" id="btn-confirm-delete">Hapus</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- /.card-body -->
            </div>
        </div>
    </section>

    {{-- unpost memo  --}}
    <script>
        $(document).ready(function() {
            $('.unpost-btn').click(function() {
                var memoId = $(this).data('memo-id');

                // Tampilkan modal loading saat permintaan AJAX diproses
                $('#modal-loading').modal('show');

                // Kirim permintaan AJAX untuk melakukan unpost
                $.ajax({
                    url: "{{ url('admin/inquery-penjualan/unpostpenjualan/') }}/" +
                        memoId,
                    type: 'GET',
                    data: {
                        id: memoId
                    },
                    success: function(response) {
                        // Sembunyikan modal loading setelah permintaan selesai
                        $('#modal-loading').modal('hide');

                        // Tampilkan pesan sukses atau lakukan tindakan lain sesuai kebutuhan
                        console.log(response);

                        // Tutup modal setelah berhasil unpost
                        $('#modal-posting-' + memoId).modal('hide');

                        // Reload the page to refresh the table
                        location.reload();
                    },
                    error: function(error) {
                        // Sembunyikan modal loading setelah permintaan selesai
                        $('#modal-loading').modal('hide');

                        // Tampilkan pesan error atau lakukan tindakan lain sesuai kebutuhan
                        console.log(error);
                    }
                });
            });
        });
    </script>
    {{-- posting memo --}}
    <script>
        $(document).ready(function() {
            $('.posting-btn').click(function() {
                var memoId = $(this).data('memo-id');

                // Tampilkan modal loading saat permintaan AJAX diproses
                $('#modal-loading').modal('show');

                // Kirim permintaan AJAX untuk melakukan posting
                $.ajax({
                    url: "{{ url('admin/inquery-penjualan/postingpenjualan/') }}/" +
                        memoId,
                    type: 'GET',
                    data: {
                        id: memoId
                    },
                    success: function(response) {
                        // Sembunyikan modal loading setelah permintaan selesai
                        $('#modal-loading').modal('hide');

                        // Tampilkan pesan sukses atau lakukan tindakan lain sesuai kebutuhan
                        console.log(response);

                        // Tutup modal setelah berhasil posting
                        $('#modal-posting-' + memoId).modal('hide');

                        // Reload the page to refresh the table
                        location.reload();
                    },
                    error: function(error) {
                        // Sembunyikan modal loading setelah permintaan selesai
                        $('#modal-loading').modal('hide');

                        // Tampilkan pesan error atau lakukan tindakan lain sesuai kebutuhan
                        console.log(error);
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('tbody tr.dropdown').click(function(e) {
                // Memeriksa apakah yang diklik adalah checkbox
                if ($(e.target).is('input[type="checkbox"]')) {
                    return; // Jika ya, hentikan eksekusi
                }

                // Menghapus kelas 'selected' dan mengembalikan warna latar belakang ke warna default dari semua baris
                $('tr.dropdown').removeClass('selected').css('background-color', '');

                // Menambahkan kelas 'selected' ke baris yang dipilih dan mengubah warna latar belakangnya
                $(this).addClass('selected').css('background-color', '#b0b0b0');

                // Menyembunyikan dropdown pada baris lain yang tidak dipilih
                $('tbody tr.dropdown').not(this).find('.dropdown-menu').hide();

                // Mencegah event klik menyebar ke atas (misalnya, saat mengklik dropdown)
                e.stopPropagation();
            });

            $('tbody tr.dropdown').contextmenu(function(e) {
                // Memeriksa apakah baris ini memiliki kelas 'selected'
                if ($(this).hasClass('selected')) {
                    // Menampilkan dropdown saat klik kanan
                    var dropdownMenu = $(this).find('.dropdown-menu');
                    dropdownMenu.show();

                    // Mendapatkan posisi td yang diklik
                    var clickedTd = $(e.target).closest('td');
                    var tdPosition = clickedTd.position();

                    // Menyusun posisi dropdown relatif terhadap td yang di klik
                    dropdownMenu.css({
                        'position': 'absolute',
                        'top': tdPosition.top + clickedTd
                            .height(), // Menempatkan dropdown sedikit di bawah td yang di klik
                        'left': tdPosition
                            .left // Menempatkan dropdown di sebelah kiri td yang di klik
                    });

                    // Mencegah event klik kanan menyebar ke atas (misalnya, saat mengklik dropdown)
                    e.stopPropagation();
                    e.preventDefault(); // Mencegah munculnya konteks menu bawaan browser
                }
            });

            // Menyembunyikan dropdown saat klik di tempat lain
            $(document).click(function() {
                $('.dropdown-menu').hide();
                $('tr.dropdown').removeClass('selected').css('background-color',
                    ''); // Menghapus warna latar belakang dari semua baris saat menutup dropdown
            });
        });
    </script>

@endsection
