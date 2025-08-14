<li class="nav-header">
    Dashboard</li>
<li class="nav-item">
    <a href="{{ url('admin') }}" class="nav-link {{ request()->is('admin') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>
            Dashboard
        </p>
    </a>
</li>
<li class="nav-header">Search</li>

<div class="form-inline mb-2">
    <div class="input-group">
        <input id="sidebarSearchInput" class="form-control form-control-sidebar" type="search" placeholder="Search Menu"
            aria-label="Search">
        <div class="input-group-append">
            <button class="btn btn-sidebar" type="button">
                <i class="fas fa-search fa-fw"></i>
            </button>
        </div>
    </div>
</div>

<li class="nav-header">Menu</li>
<li class="nav-item {{ request()->is('admin/karyawan*') ||
    request()->is('admin/user*') ||
    request()->is('admin/akses*') ||
    request()->is('admin/departemen*') ||
    request()->is('admin/satuan*') ||
    request()->is('admin/pelanggan*') ||
    request()->is('admin/supplier*') ||
    request()->is('admin/merek*') ||
    request()->is('admin/type*') ||
    request()->is('admin/bagian*') ||
    request()->is('admin/harga*') ||
    request()->is('admin/pelanggan*') ||
    request()->is('admin/barang*')
        ? 'menu-open'
        : '' }}">
    <a href="#" class="nav-link {{ request()->is('admin/karyawan*') ||
        request()->is('admin/user*') ||
        request()->is('admin/akses*') ||
        request()->is('admin/departemen*') ||
        request()->is('admin/satuan*') ||
        request()->is('admin/pelanggan*') ||
        request()->is('admin/supplier*') ||
        request()->is('admin/merek*') ||
        request()->is('admin/type*') ||
        request()->is('admin/bagian*') ||
        request()->is('admin/harga*') ||
        request()->is('admin/pelanggan*') ||
        request()->is('admin/barang*')
            ? 'active'
            : '' }}">

        <i class="nav-icon fas fa-grip-horizontal"></i>
        <p>
            <strong style="color: rgb(255, 255, 255);">MASTER</strong>
            <i class="right fas fa-angle-left"></i>
        </p>

    </a>
    <ul class="nav nav-treeview">
        @foreach (Auth::user()->menufiturs->where('kategori', 'MASTER') as $menu)
        <li class="nav-item sidebar-submenu-item" data-name="{{ strtolower($menu->nama) }}">
            <a href="{{ url('admin/' . $menu->route) }}"
                class="nav-link {{ request()->is('admin/' . $menu->route . '*') ? 'active' : '' }}"
                style="display: flex; align-items: center;">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px; margin-left: 3px; word-break: break-word; max-width: 150px;">
                    {{ $menu->nama }}
                </p>
            </a>
        </li>
        @endforeach
        @if (auth()->user()->id == 1)
        <li class="nav-item">
            <a href="{{ url('admin/menu-fitur') }}"
                class="nav-link {{ request()->is('admin/menu-fitur*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Menu Fitur</p>
            </a>
        </li>
        @endif
    </ul>
</li>

<li
    class="nav-item {{ request()->is('admin/pembelian*') || request()->is('admin/po-pembelian*') || request()->is('admin/pelunasan-pembelian*') || request()->is('admin/penjualan*') ? 'menu-open' : '' }}">
    <a href="#"
        class="nav-link {{ request()->is('admin/pembelian*') || request()->is('admin/po-pembelian*') || request()->is('admin/pelunasan-pembelian*') || request()->is('admin/penjualan*') ? 'active' : '' }}">

        <i class="nav-icon fas fa-exchange-alt"></i>
        <p>
            <strong style="color: rgb(255, 255, 255);">TRANSAKSI</strong>
            <i class="right fas fa-angle-left"></i>
        </p>

    </a>
    <ul class="nav nav-treeview">
        @foreach (Auth::user()->menufiturs->where('kategori', 'TRANSAKSI') as $menu)
        <li class="nav-item sidebar-submenu-item" data-name="{{ strtolower($menu->nama) }}">
            <a href="{{ url('admin/' . $menu->route) }}"
                class="nav-link {{ request()->is('admin/' . $menu->route . '*') ? 'active' : '' }}"
                style="display: flex; align-items: center;">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px; margin-left: 3px; word-break: break-word; max-width: 150px;">
                    {{ $menu->nama }}
                </p>
            </a>
        </li>
        @endforeach
    </ul>
</li>

<li
    class="nav-item {{ request()->is('admin/inquery-popembelian*') || request()->is('admin/inquery-pembelian*') || request()->is('admin/inquery-pelunasan-pembelian*') || request()->is('admin/inquery-penjualan*') ? 'menu-open' : '' }}">
    <a href="#"
        class="nav-link {{ request()->is('admin/inquery-popembelian*') || request()->is('admin/inquery-pembelian*') || request()->is('admin/inquery-pelunasan-pembelian*') || request()->is('admin/inquery-penjualan*') ? 'active' : '' }}">

        <i class="nav-icon fas fa-exchange-alt"></i>
        <p>
            <strong style="color: rgb(255, 255, 255);">FINANCE</strong>
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        @foreach (Auth::user()->menufiturs->where('kategori', 'FINANCE') as $menu)
        <li class="nav-item sidebar-submenu-item" data-name="{{ strtolower($menu->nama) }}">
            <a href="{{ url('admin/' . $menu->route) }}"
                class="nav-link {{ request()->is('admin/' . $menu->route . '*') ? 'active' : '' }}"
                style="display: flex; align-items: center;">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px; margin-left: 3px; word-break: break-word; max-width: 150px;">
                    {{ $menu->nama }}
                </p>
            </a>
        </li>
        @endforeach
    </ul>
</li>

<li
    class="nav-item {{ request()->is('admin/laporan-popembelian*') || request()->is('admin/laporan-pembelian*') || request()->is('admin/laporan-pelunasan-pembelian*') || request()->is('admin/laporan-penjualan*') ? 'menu-open' : '' }}">
    <a href="#"
        class="nav-link {{ request()->is('admin/laporan-popembelian*') || request()->is('admin/laporan-pembelian*') || request()->is('admin/laporan-pelunasan-pembelian*') || request()->is('admin/laporan-penjualan*') ? 'active' : '' }}">

        <i class="nav-icon fas fa-clipboard-list"></i>
        <p>
            <strong style="color: rgb(255, 255, 255);">LAPORAN</strong>
            <i class="right fas fa-angle-left"></i>
        </p>

    </a>
    <ul class="nav nav-treeview">
        @foreach (Auth::user()->menufiturs->where('kategori', 'LAPORAN') as $menu)
        <li class="nav-item sidebar-submenu-item" data-name="{{ strtolower($menu->nama) }}">
            <a href="{{ url('admin/' . $menu->route) }}"
                class="nav-link {{ request()->is('admin/' . $menu->route . '*') ? 'active' : '' }}"
                style="display: flex; align-items: center;">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px; margin-left: 3px; word-break: break-word; max-width: 150px;">
                    {{ $menu->nama }}
                </p>
            </a>
        </li>
        @endforeach
    </ul>
</li>
<li class="nav-header">Profile</li>
<li class="nav-item">
    <a href="{{ url('admin/profile') }}" class="nav-link {{ request()->is('admin/profile') ? 'active' : '' }}">
        <i class="nav-icon fas fa-user-edit"></i>
        <p>Update Profile</p>
    </a>
<li class="nav-item">
    <a href="#" data-toggle="modal" data-target="#modalLogout" class="nav-link">
        <i class="nav-icon fas fa-sign-out-alt"></i>
        <p>Logout</p>
    </a>
</li>

<script>
document.getElementById('sidebarSearchInput').addEventListener('input', function() {
    const keyword = this.value.toLowerCase();
    const items = document.querySelectorAll('.sidebar-submenu-item');

    items.forEach(item => {
        const name = item.getAttribute('data-name');
        if (name.includes(keyword)) {
            item.style.display = 'block';
            // Buka parent menu jika match
            item.closest('.nav-treeview').style.display = 'block';
            item.closest('.nav-item').classList.add('menu-open');
        } else {
            item.style.display = 'none';
        }
    });

    if (keyword === '') {
        // Reset: tutup semua jika kosong
        document.querySelectorAll('.nav-treeview').forEach(ul => ul.style.display = '');
        document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('menu-open'));
    }
});
</script>