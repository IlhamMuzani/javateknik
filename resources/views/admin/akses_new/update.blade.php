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

                <label style="margin-left: 22px; margin-top: 15px" for="option-all">Nama Karyawan :
                    {{ $user->karyawan->nama_lengkap ?? null }}</label>

                <div class="card-body">

                    <div class="table-responsive" style="overflow-x: auto;">
                        <input type="text" id="searchMenu" class="form-control mb-3" placeholder="Cari menu...">
                        <form action="{{ url('admin/akses-new/' . $user->id) }}" method="POST"
                            enctype="multipart/form-data" autocomplete="off">
                            @csrf
                            @method('PUT')

                            {{-- <table class="table">
                                <tbody>
                                    @foreach ($menufiturs as $kategori => $menus)
                                        <tr class="table-secondary">
                                            <td colspan="12">
                                                <input type="checkbox" class="category-checkbox"
                                                    id="category-{{ strtolower($kategori) }}">
                                                <b>{{ ucfirst($kategori) }}</b>
                                            </td>
                                        </tr>
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

                                        @foreach ($menus as $index => $menu)
                                            <tr class="menu-group-{{ strtolower($menu->kategori) }} menu-row">
                                                <td>{{ ++$index }}</td>
                                                <td>
                                                    <input type="hidden" name="menus[{{ $menu->id }}]" value="0">
                                                    <input type="checkbox"
                                                        class="menu-checkbox category-{{ strtolower($menu->kategori) }}"
                                                        name="menus[{{ $menu->id }}]" value="{{ $menu->id }}"
                                                        data-menu="{{ $menu->id }}"
                                                        {{ isset($userMenu[$menu->id]) ? 'checked' : '' }}>
                                                    <span class="menu-name">{{ $menu->nama }}</span>
                                                </td>

                                                @if (in_array(strtolower($menu->kategori), ['master', 'operasional', 'pemeliharaan kendaraan', 'transaksi', 'finance']))
                                                    <td><input type="checkbox"
                                                            class="submenu-checkbox category-{{ strtolower($menu->kategori) }}"
                                                            name="permissions[{{ $menu->id }}][create]"
                                                            data-menu="{{ $menu->id }}" 
                                                            {{ isset($userMenu[$menu->id]) && $userMenu[$menu->id]->pivot->can_create ? 'checked' : '' }}>
                                                    </td>
                                                    <td><input type="checkbox"
                                                            class="submenu-checkbox category-{{ strtolower($menu->kategori) }}"
                                                            name="permissions[{{ $menu->id }}][update]"
                                                            data-menu="{{ $menu->id }}" 
                                                            {{ isset($userMenu[$menu->id]) && $userMenu[$menu->id]->pivot->can_update ? 'checked' : '' }}>
                                                    </td>
                                                    <td><input type="checkbox"
                                                            class="submenu-checkbox category-{{ strtolower($menu->kategori) }}"
                                                            name="permissions[{{ $menu->id }}][delete]"
                                                            data-menu="{{ $menu->id }}" 
                                                            {{ isset($userMenu[$menu->id]) && $userMenu[$menu->id]->pivot->can_delete ? 'checked' : '' }}>
                                                    </td>
                                                    <td><input type="checkbox"
                                                            class="submenu-checkbox category-{{ strtolower($menu->kategori) }}"
                                                            name="permissions[{{ $menu->id }}][show]"
                                                            data-menu="{{ $menu->id }}" 
                                                            {{ isset($userMenu[$menu->id]) && $userMenu[$menu->id]->pivot->can_show ? 'checked' : '' }}>
                                                    </td>
                                                @else
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                @endif

                                                @if (in_array(strtolower($menu->kategori), ['operasional', 'pemeliharaan kendaraan', 'transaksi', 'finance']))
                                                    <td><input type="checkbox"
                                                            class="submenu-checkbox category-{{ strtolower($menu->kategori) }}"
                                                            name="permissions[{{ $menu->id }}][posting]"
                                                            data-menu="{{ $menu->id }}" 
                                                            {{ isset($userMenu[$menu->id]) && $userMenu[$menu->id]->pivot->can_posting ? 'checked' : '' }}>
                                                    </td>
                                                    <td><input type="checkbox"
                                                            class="submenu-checkbox category-{{ strtolower($menu->kategori) }}"
                                                            name="permissions[{{ $menu->id }}][unpost]"
                                                            data-menu="{{ $menu->id }}" 
                                                            {{ isset($userMenu[$menu->id]) && $userMenu[$menu->id]->pivot->can_unpost ? 'checked' : '' }}>
                                                    </td>
                                                    <td><input type="checkbox"
                                                            class="submenu-checkbox category-{{ strtolower($menu->kategori) }}"
                                                            name="permissions[{{ $menu->id }}][posting_all]"
                                                            data-menu="{{ $menu->id }}" 
                                                            {{ isset($userMenu[$menu->id]) && $userMenu[$menu->id]->pivot->can_posting_all ? 'checked' : '' }}>
                                                    </td>
                                                    <td><input type="checkbox"
                                                            class="submenu-checkbox category-{{ strtolower($menu->kategori) }}"
                                                            name="permissions[{{ $menu->id }}][unpost_all]"
                                                            data-menu="{{ $menu->id }}" 
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
                                                            data-menu="{{ $menu->id }}"
                                                            {{ isset($userMenu[$menu->id]) && $userMenu[$menu->id]->pivot->can_search ? 'checked' : '' }}>
                                                    </td>
                                                    <td><input type="checkbox"
                                                            class="submenu-checkbox category-{{ strtolower($menu->kategori) }}"
                                                            name="permissions[{{ $menu->id }}][print]"
                                                            data-menu="{{ $menu->id }}" 
                                                            {{ isset($userMenu[$menu->id]) && $userMenu[$menu->id]->pivot->can_print ? 'checked' : '' }}>
                                                    </td>
                                                @else
                                                    <td></td>
                                                    <td></td>
                                                @endif
                                            </tr>
                                        @endforeach

                                        <tr>
                                            <td colspan="12" style="height: 20px;"></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table> --}}

                            @php
                                $orderedCategories = [
                                    'MASTER',
                                    'OPERASIONAL',
                                    'PEMELIHARAAN_KENDARAAN',
                                    'TRANSAKSI',
                                    'FINANCE',
                                    'LAPORAN',
                                ];

                                // Urutkan kategori sesuai urutan yang diinginkan
                                $sortedMenus = [];
                                foreach ($orderedCategories as $category) {
                                    if (isset($menufiturs[$category])) {
                                        $sortedMenus[$category] = $menufiturs[$category];
                                    }
                                }
                            @endphp

                            <table class="table">
                                <tbody>
                                    @foreach ($sortedMenus as $kategori => $menus)
                                        {{-- Judul kategori dengan checkbox --}}
                                        <tr class="table-secondary">
                                            <td colspan="12">
                                                <input type="checkbox" class="category-checkbox"
                                                    id="category-{{ strtolower($kategori) }}">
                                                <b>{{ ucfirst($kategori) }}</b>
                                            </td>
                                        </tr>
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

                                        @foreach ($menus as $index => $menu)
                                            {{-- Baris data menu --}}
                                            <tr class="menu-group-{{ strtolower($menu->kategori) }} menu-row">
                                                <td>{{ ++$index }}</td>
                                                <td>
                                                    <input type="hidden" name="menus[{{ $menu->id }}]" value="0">
                                                    <input type="checkbox"
                                                        class="menu-checkbox category-{{ strtolower($menu->kategori) }}"
                                                        name="menus[{{ $menu->id }}]" value="{{ $menu->id }}"
                                                        data-menu="{{ $menu->id }}"
                                                        {{ isset($userMenu[$menu->id]) ? 'checked' : '' }}>
                                                    <span class="menu-name">{{ $menu->nama }}</span>
                                                </td>

                                                {{-- Hak akses untuk kategori tertentu --}}
                                                @if (in_array(strtoupper($menu->kategori), ['MASTER', 'OPERASIONAL', 'PEMELIHARAAN_KENDARAAN', 'TRANSAKSI']))
                                                    <td><input type="checkbox"
                                                            class="submenu-checkbox category-{{ strtolower($menu->kategori) }}"
                                                            name="permissions[{{ $menu->id }}][create]"
                                                            data-menu="{{ $menu->id }}"
                                                            {{ isset($userMenu[$menu->id]) && $userMenu[$menu->id]->pivot->can_create ? 'checked' : '' }}>
                                                    </td>
                                                @else
                                                    <td></td>
                                                @endif


                                                @if (in_array(strtoupper($menu->kategori), ['MASTER', 'OPERASIONAL', 'PEMELIHARAAN_KENDARAAN', 'TRANSAKSI','FINANCE']))
                                                    <td><input type="checkbox"
                                                            class="submenu-checkbox category-{{ strtolower($menu->kategori) }}"
                                                            name="permissions[{{ $menu->id }}][update]"
                                                            data-menu="{{ $menu->id }}"
                                                            {{ isset($userMenu[$menu->id]) && $userMenu[$menu->id]->pivot->can_update ? 'checked' : '' }}>
                                                    </td>
                                                    <td><input type="checkbox"
                                                            class="submenu-checkbox category-{{ strtolower($menu->kategori) }}"
                                                            name="permissions[{{ $menu->id }}][delete]"
                                                            data-menu="{{ $menu->id }}"
                                                            {{ isset($userMenu[$menu->id]) && $userMenu[$menu->id]->pivot->can_delete ? 'checked' : '' }}>
                                                    </td>
                                                    <td><input type="checkbox"
                                                            class="submenu-checkbox category-{{ strtolower($menu->kategori) }}"
                                                            name="permissions[{{ $menu->id }}][show]"
                                                            data-menu="{{ $menu->id }}"
                                                            {{ isset($userMenu[$menu->id]) && $userMenu[$menu->id]->pivot->can_show ? 'checked' : '' }}>
                                                    </td>
                                                @else
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                @endif

                                                @if (in_array(strtoupper($menu->kategori), ['OPERASIONAL', 'PEMELIHARAAN_KENDARAAN', 'TRANSAKSI', 'FINANCE']))
                                                    <td><input type="checkbox"
                                                            class="submenu-checkbox category-{{ strtolower($menu->kategori) }}"
                                                            name="permissions[{{ $menu->id }}][posting]"
                                                            data-menu="{{ $menu->id }}"
                                                            {{ isset($userMenu[$menu->id]) && $userMenu[$menu->id]->pivot->can_posting ? 'checked' : '' }}>
                                                    </td>
                                                    <td><input type="checkbox"
                                                            class="submenu-checkbox category-{{ strtolower($menu->kategori) }}"
                                                            name="permissions[{{ $menu->id }}][unpost]"
                                                            data-menu="{{ $menu->id }}"
                                                            {{ isset($userMenu[$menu->id]) && $userMenu[$menu->id]->pivot->can_unpost ? 'checked' : '' }}>
                                                    </td>
                                                @else
                                                    <td></td>
                                                    <td></td>
                                                @endif


                                                @if (in_array(strtoupper($menu->kategori), ['FINANCE']))
                                                    <td><input type="checkbox"
                                                            class="submenu-checkbox category-{{ strtolower($menu->kategori) }}"
                                                            name="permissions[{{ $menu->id }}][posting_all]"
                                                            data-menu="{{ $menu->id }}"
                                                            {{ isset($userMenu[$menu->id]) && $userMenu[$menu->id]->pivot->can_posting_all ? 'checked' : '' }}>
                                                    </td>
                                                    <td><input type="checkbox"
                                                            class="submenu-checkbox category-{{ strtolower($menu->kategori) }}"
                                                            name="permissions[{{ $menu->id }}][unpost_all]"
                                                            data-menu="{{ $menu->id }}"
                                                            {{ isset($userMenu[$menu->id]) && $userMenu[$menu->id]->pivot->can_unpost_all ? 'checked' : '' }}>
                                                    </td>
                                                @else
                                                    <td></td>
                                                    <td></td>
                                                @endif

                                                @if (strtoupper($menu->kategori) === 'LAPORAN')
                                                    <td><input type="checkbox"
                                                            class="submenu-checkbox category-{{ strtolower($menu->kategori) }}"
                                                            name="permissions[{{ $menu->id }}][search]"
                                                            data-menu="{{ $menu->id }}"
                                                            {{ isset($userMenu[$menu->id]) && $userMenu[$menu->id]->pivot->can_search ? 'checked' : '' }}>
                                                    </td>
                                                    <td><input type="checkbox"
                                                            class="submenu-checkbox category-{{ strtolower($menu->kategori) }}"
                                                            name="permissions[{{ $menu->id }}][print]"
                                                            data-menu="{{ $menu->id }}"
                                                            {{ isset($userMenu[$menu->id]) && $userMenu[$menu->id]->pivot->can_print ? 'checked' : '' }}>
                                                    </td>
                                                @else
                                                    <td></td>
                                                    <td></td>
                                                @endif
                                            </tr>
                                        @endforeach

                                        {{-- Baris pemisah antar kategori --}}
                                        <tr>
                                            <td colspan="12" style="height: 20px;"></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="text-right">
                                <button type="reset" class="btn btn-secondary" id="btnReset">Reset</button>
                                <button type="submit" class="btn btn-primary" id="btnSimpan">Simpan</button>
                                <div id="loading" style="display: none;">
                                    <i class="fas fa-spinner fa-spin"></i> Sedang Menyimpan...
                                </div>
                            </div>
                            {{-- <button type="submit" class="btn btn-primary">Simpan Akses</button> --}}
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

            // Event listener untuk checkbox kategori (judul kategori seperti "Master")
            document.querySelectorAll('.category-checkbox').forEach(function(categoryCheckbox) {
                categoryCheckbox.addEventListener('change', function() {
                    let kategori = this.id.replace('category-', '');
                    let isChecked = categoryCheckbox.checked;

                    // Ambil semua menu dan submenu dalam kategori ini
                    let menuCheckboxes = document.querySelectorAll('.menu-checkbox.category-' +
                        kategori);
                    let submenuCheckboxes = document.querySelectorAll(
                        '.submenu-checkbox.category-' + kategori);

                    // Centang atau hilangkan centang semua dalam grup
                    menuCheckboxes.forEach(menu => {
                        menu.checked = isChecked;

                        // Update submenu berdasarkan status menu utama
                        let submenuList = document.querySelectorAll(
                            `.submenu-checkbox[data-menu="${menu.dataset.menu}"]`);
                        submenuList.forEach(submenu => submenu.checked = isChecked);
                    });

                    submenuCheckboxes.forEach(submenu => submenu.checked = isChecked);
                });
            });

            // Event listener untuk checkbox menu utama
            document.querySelectorAll('.menu-checkbox').forEach(function(menuCheckbox) {
                menuCheckbox.addEventListener('change', function() {
                    let kategori = this.classList[1].replace('category-', '');
                    let isChecked = this.checked;

                    // Update submenu berdasarkan menu utama
                    let submenuCheckboxes = document.querySelectorAll(
                        `.submenu-checkbox[data-menu="${this.dataset.menu}"]`);
                    submenuCheckboxes.forEach(submenu => submenu.checked = isChecked);

                    updateCategoryCheckbox(kategori);
                });
            });

            // Event listener untuk checkbox submenu
            document.querySelectorAll('.submenu-checkbox').forEach(function(submenuCheckbox) {
                submenuCheckbox.addEventListener('change', function() {
                    let menuCheckbox = document.querySelector(
                        `.menu-checkbox[data-menu="${this.dataset.menu}"]`);

                    if (menuCheckbox) {
                        // Jika semua submenu tidak dicentang, menu utama tetap dicentang
                        let submenuList = document.querySelectorAll(
                            `.submenu-checkbox[data-menu="${menuCheckbox.dataset.menu}"]`);
                        let anySubmenuChecked = Array.from(submenuList).some(sub => sub.checked);

                        // Menu utama **tidak boleh hilang centangnya** kecuali diubah manual
                        if (!anySubmenuChecked) {
                            menuCheckbox.checked = true; // Pastikan menu utama tetap dicentang
                        }
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

    <!-- /.card -->
@endsection
