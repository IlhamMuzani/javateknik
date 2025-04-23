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
                                                    data-menu="{{ $menu->id }}" {{-- Tambahkan ini --}}
                                                    {{ isset($userMenu[$menu->id]) ? 'checked' : '' }}>
                                                <span class="menu-name">{{ $menu->nama }}</span>
                                            </td>

                                            {{-- Hak akses untuk kategori tertentu --}}
                                            @if (in_array(strtolower($menu->kategori), ['master', 'transaksi', 'finance']))
                                                <td><input type="checkbox"
                                                        class="submenu-checkbox category-{{ strtolower($menu->kategori) }}"
                                                        name="permissions[{{ $menu->id }}][create]"
                                                        data-menu="{{ $menu->id }}" {{-- Tambahkan ini --}}
                                                        {{ isset($userMenu[$menu->id]) && $userMenu[$menu->id]->pivot->can_create ? 'checked' : '' }}>
                                                </td>
                                                <td><input type="checkbox"
                                                        class="submenu-checkbox category-{{ strtolower($menu->kategori) }}"
                                                        name="permissions[{{ $menu->id }}][update]"
                                                        data-menu="{{ $menu->id }}" {{-- Tambahkan ini --}}
                                                        {{ isset($userMenu[$menu->id]) && $userMenu[$menu->id]->pivot->can_update ? 'checked' : '' }}>
                                                </td>
                                                <td><input type="checkbox"
                                                        class="submenu-checkbox category-{{ strtolower($menu->kategori) }}"
                                                        name="permissions[{{ $menu->id }}][delete]"
                                                        data-menu="{{ $menu->id }}" {{-- Tambahkan ini --}}
                                                        {{ isset($userMenu[$menu->id]) && $userMenu[$menu->id]->pivot->can_delete ? 'checked' : '' }}>
                                                </td>
                                                <td><input type="checkbox"
                                                        class="submenu-checkbox category-{{ strtolower($menu->kategori) }}"
                                                        name="permissions[{{ $menu->id }}][show]"
                                                        data-menu="{{ $menu->id }}" {{-- Tambahkan ini --}}
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
            function updateCategoryCheckbox(kategori) {
                let menuCheckboxes = document.querySelectorAll('.menu-checkbox.category-' + kategori);
                let categoryCheckbox = document.getElementById('category-' + kategori);
                let anyChecked = Array.from(menuCheckboxes).some(menu => menu.checked);
                if (categoryCheckbox) {
                    categoryCheckbox.checked = anyChecked;
                }
            }

            // Event listener untuk checkbox kategori (Check All tiap kategori)
            document.querySelectorAll('.category-checkbox').forEach(function(categoryCheckbox) {
                categoryCheckbox.addEventListener('change', function() {
                    let kategori = this.id.replace('category-', '');
                    let menuCheckboxes = document.querySelectorAll('.menu-checkbox.category-' +
                        kategori);
                    let submenuCheckboxes = document.querySelectorAll(
                        '.submenu-checkbox.category-' + kategori);
                    let isChecked = categoryCheckbox.checked;

                    // Centang semua menu dan submenu dalam kategori yang dipilih
                    menuCheckboxes.forEach(menu => {
                        menu.checked = isChecked;

                        // Centang semua submenu terkait
                        let submenuCheckboxesForMenu = document.querySelectorAll(
                            `.submenu-checkbox[data-menu="${menu.dataset.menu}"]`);
                        submenuCheckboxesForMenu.forEach(submenu => submenu.checked =
                            isChecked);
                    });

                    submenuCheckboxes.forEach(submenu => submenu.checked = isChecked);
                });
            });

            // Event listener untuk checkbox menu individual
            document.querySelectorAll('.menu-checkbox').forEach(function(menuCheckbox) {
                menuCheckbox.addEventListener('change', function() {
                    let kategori = this.classList[1].replace('category-', '');
                    let isChecked = this.checked;

                    // Update semua submenu terkait dengan menu yang dicentang/dicek
                    let submenuCheckboxes = document.querySelectorAll(
                        `.submenu-checkbox[data-menu="${this.dataset.menu}"]`);
                    submenuCheckboxes.forEach(submenu => submenu.checked = isChecked);

                    updateCategoryCheckbox(kategori);
                });
            });

            // Event listener untuk checkbox submenu (hak akses seperti create, update, delete, dll.)
            document.querySelectorAll('.submenu-checkbox').forEach(function(submenuCheckbox) {
                submenuCheckbox.addEventListener('change', function() {
                    let menuCheckbox = document.querySelector(
                        `.menu-checkbox[data-menu="${this.dataset.menu}"]`);

                    if (menuCheckbox) {
                        // Jika ada satu submenu yang dicentang, maka menu utama harus ikut dicentang
                        let submenuList = document.querySelectorAll(
                            `.submenu-checkbox[data-menu="${menuCheckbox.dataset.menu}"]`);
                        menuCheckbox.checked = Array.from(submenuList).some(sub => sub.checked);
                    }

                    let kategori = this.classList[1].replace('category-', '');
                    updateCategoryCheckbox(kategori);
                });
            });

            // Periksa status checkbox kategori saat halaman dimuat
            document.querySelectorAll('.category-checkbox').forEach(function(categoryCheckbox) {
                let kategori = categoryCheckbox.id.replace('category-', '');
                updateCategoryCheckbox(kategori);
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
