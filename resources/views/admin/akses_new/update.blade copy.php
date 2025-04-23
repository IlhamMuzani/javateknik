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
                        <input type="text" id="searchMenu" class="form-control mb-3" placeholder="Cari menu...">
                        <form action="{{ url('admin/akses-new/' . $user->id) }}" method="POST"
                            enctype="multipart/form-data" autocomplete="off">
                            @csrf
                            @method('PUT')

                            <table class="table">
                                <tbody>
                                    @php
                                        $kategoriSebelumnya = null;
                                        $index = 0;
                                    @endphp

                                    @foreach ($menufiturs as $menu)
                                        @if ($kategoriSebelumnya !== strtolower($menu->kategori))
                                            {{-- Baris pemisah antar kategori --}}
                                            @if ($kategoriSebelumnya !== null)
                                                <tr>
                                                    <td colspan="12" style="height: 20px;"></td>
                                                </tr>
                                            @endif

                                            {{-- Judul kategori dengan checkbox --}}
                                            <tr class="table-secondary">
                                                <td colspan="12">
                                                    <input type="checkbox" class="category-checkbox"
                                                        id="category-{{ strtolower($menu->kategori) }}">
                                                    <b>{{ ucfirst($menu->kategori) }}</b>
                                                </td>
                                            </tr>

                                            {{-- Tambahkan Header Tabel Kembali --}}
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Menu</th>
                                                <th>Create</th>
                                                <th>Update</th>
                                                <th>Delete</th>
                                                <th>Show</th>
                                                <th>Posting</th>
                                                <th>Unpost</th>
                                                <th>Posting All</th>
                                                <th>Unpost All</th>
                                                <th>Search</th>
                                                <th>Print</th>
                                            </tr>

                                            @php $index = 0; @endphp
                                        @endif

                                        {{-- Baris data menu --}}
                                        <tr class="menu-group-{{ strtolower($menu->kategori) }} menu-row">
                                            <td>{{ ++$index }}</td>
                                            <td>
                                                <input type="hidden" name="menus[{{ $menu->id }}]" value="0">
                                                <input type="checkbox"
                                                    class="menu-checkbox category-{{ strtolower($menu->kategori) }}"
                                                    name="menus[{{ $menu->id }}]" value="{{ $menu->id }}"
                                                    {{ isset($userMenu[$menu->id]) ? 'checked' : '' }}>
                                                <span class="menu-name">{{ $menu->nama }}</span>
                                            </td>
                                            {{-- Hak akses untuk kategori tertentu --}}
                                            @if (in_array(strtolower($menu->kategori), ['master', 'transaksi', 'finance']))
                                                <td><input type="checkbox"
                                                        class="submenu-checkbox category-{{ strtolower($menu->kategori) }}"
                                                        name="permissions[{{ $menu->id }}][create]"
                                                        {{ isset($userMenu[$menu->id]) && $userMenu[$menu->id]->pivot->can_create ? 'checked' : '' }}>
                                                </td>
                                                <td><input type="checkbox"
                                                        class="submenu-checkbox category-{{ strtolower($menu->kategori) }}"
                                                        name="permissions[{{ $menu->id }}][update]"
                                                        {{ isset($userMenu[$menu->id]) && $userMenu[$menu->id]->pivot->can_update ? 'checked' : '' }}>
                                                </td>
                                                <td><input type="checkbox"
                                                        class="submenu-checkbox category-{{ strtolower($menu->kategori) }}"
                                                        name="permissions[{{ $menu->id }}][delete]"
                                                        {{ isset($userMenu[$menu->id]) && $userMenu[$menu->id]->pivot->can_delete ? 'checked' : '' }}>
                                                </td>
                                                <td><input type="checkbox"
                                                        class="submenu-checkbox category-{{ strtolower($menu->kategori) }}"
                                                        name="permissions[{{ $menu->id }}][show]"
                                                        {{ isset($userMenu[$menu->id]) && $userMenu[$menu->id]->pivot->can_show ? 'checked' : '' }}>
                                                </td>
                                            @else
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            @endif

                                            @if (in_array(strtolower($menu->kategori), ['transaksi', 'finance']))
                                                <td><input type="checkbox"
                                                        class="submenu-checkbox category-{{ strtolower($menu->kategori) }}"
                                                        name="permissions[{{ $menu->id }}][posting]"
                                                        {{ isset($userMenu[$menu->id]) && $userMenu[$menu->id]->pivot->can_posting ? 'checked' : '' }}>
                                                </td>
                                                <td><input type="checkbox"
                                                        class="submenu-checkbox category-{{ strtolower($menu->kategori) }}"
                                                        name="permissions[{{ $menu->id }}][unpost]"
                                                        {{ isset($userMenu[$menu->id]) && $userMenu[$menu->id]->pivot->can_unpost ? 'checked' : '' }}>
                                                </td>
                                                <td><input type="checkbox"
                                                        class="submenu-checkbox category-{{ strtolower($menu->kategori) }}"
                                                        name="permissions[{{ $menu->id }}][posting_all]"
                                                        {{ isset($userMenu[$menu->id]) && $userMenu[$menu->id]->pivot->can_posting_all ? 'checked' : '' }}>
                                                </td>
                                                <td><input type="checkbox"
                                                        class="submenu-checkbox category-{{ strtolower($menu->kategori) }}"
                                                        name="permissions[{{ $menu->id }}][unpost_all]"
                                                        {{ isset($userMenu[$menu->id]) && $userMenu[$menu->id]->pivot->can_unpost_all ? 'checked' : '' }}>
                                                </td>
                                            @else
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            @endif

                                            @if (strtolower($menu->kategori) === 'laporan')
                                                <td><input type="checkbox"
                                                        class="submenu-checkbox category-{{ strtolower($menu->kategori) }}"
                                                        name="permissions[{{ $menu->id }}][search]"
                                                        {{ isset($userMenu[$menu->id]) && $userMenu[$menu->id]->pivot->can_search ? 'checked' : '' }}>
                                                </td>
                                                <td><input type="checkbox"
                                                        class="submenu-checkbox category-{{ strtolower($menu->kategori) }}"
                                                        name="permissions[{{ $menu->id }}][print]"
                                                        {{ isset($userMenu[$menu->id]) && $userMenu[$menu->id]->pivot->can_print ? 'checked' : '' }}>
                                                </td>
                                            @else
                                                <td></td>
                                                <td></td>
                                            @endif
                                        </tr>

                                        @php $kategoriSebelumnya = strtolower($menu->kategori); @endphp
                                    @endforeach
                                </tbody>
                            </table>






                            <button type="submit" class="btn btn-primary">Simpan Akses</button>
                        </form>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </section>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function updateMenuCheckbox(menuId) {
                let submenus = document.querySelectorAll('.menu-' + menuId);
                let menuCheckbox = document.querySelector('.menu-checkbox[data-menu="' + menuId + '"]');

                let anyChecked = [...submenus].some(submenu => submenu.checked);

                // Jika menu ini ada di database, harus tetap tercentang
                if (menuCheckbox) {
                    if (menuCheckbox.hasAttribute('checked')) {
                        menuCheckbox.checked = true; // Tetap centang jika ada akses
                    } else {
                        menuCheckbox.checked = anyChecked; // Jika ada izin dicentang, tetap centang
                    }
                }
            }

            document.querySelectorAll('.menu-checkbox').forEach(function(menuCheckbox) {
                menuCheckbox.addEventListener('change', function() {
                    let menuId = this.getAttribute('data-menu');
                    let submenus = document.querySelectorAll('.menu-' + menuId);

                    submenus.forEach(function(submenuCheckbox) {
                        submenuCheckbox.checked = menuCheckbox.checked;
                    });
                });
            });

            document.querySelectorAll('.submenu-checkbox').forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    let menuId = this.classList[1].replace('menu-', '');
                    updateMenuCheckbox(menuId);
                });
            });

            // Periksa status checkbox saat halaman dimuat
            document.querySelectorAll('.menu-checkbox').forEach(function(menuCheckbox) {
                let menuId = menuCheckbox.getAttribute('data-menu');
                updateMenuCheckbox(menuId);
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.category-checkbox').forEach(function(categoryCheckbox) {
                categoryCheckbox.addEventListener('change', function() {
                    let category = this.id.replace("category-", ""); // Ambil nama kategori
                    let checkboxes = document.querySelectorAll('.category-' + category);

                    checkboxes.forEach(function(checkbox) {
                        checkbox.checked = categoryCheckbox.checked;
                    });
                });
            });
        });
    </script>

    <script>
        document.getElementById('searchMenu').addEventListener('keyup', function() {
            let searchValue = this.value.toLowerCase();
            let rows = document.querySelectorAll('.menu-row');

            rows.forEach(row => {
                let menuName = row.querySelector('.menu-name').textContent.toLowerCase();
                row.style.display = menuName.includes(searchValue) ? '' : 'none';
            });
        });
    </script>
    <!-- /.card -->
@endsection
