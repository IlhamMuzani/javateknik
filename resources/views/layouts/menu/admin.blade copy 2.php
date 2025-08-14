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
    <div class="input-group" style="position: relative;">
        <input id="sidebarSearchInput" class="form-control form-control-sidebar" type="search" placeholder="Search Menu"
            aria-label="Search">
        <div class="input-group-append">
            <button class="btn btn-sidebar" type="button">
                <i class="fas fa-search fa-fw"></i>
            </button>
        </div>
        <ul id="sidebarSearchResults" class="list-group"
            style="width: 100%; max-height: 600px; overflow-y: auto; position: absolute; z-index: 999; background: #343a40; color: #fff; display: none; top: 100%; left: 0;">
        </ul>
    </div>
</div>


<li class="nav-header">Menu</li>
<li
    class="nav-item {{ request()->is('admin/karyawan*') ||
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
    <a href="#"
        class="nav-link {{ request()->is('admin/karyawan*') ||
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
        const resultsContainer = document.getElementById('sidebarSearchResults');
        resultsContainer.innerHTML = '';

        if (keyword === '') {
            resultsContainer.style.display = 'none';
            return;
        }

        const items = document.querySelectorAll('.sidebar-submenu-item');
        let found = false;

        items.forEach(item => {
            const name = item.getAttribute('data-name');
            if (name.includes(keyword)) {
                found = true;
                // Tambahkan ke hasil
                const link = item.querySelector('a');
                const url = link.getAttribute('href');
                const text = link.textContent.trim();

                const li = document.createElement('li');
                li.className = 'list-group-item list-group-item-dark';
                li.style.cursor = 'pointer';
                li.textContent = text;
                li.onclick = () => {
                    window.location.href = url;
                };

                resultsContainer.appendChild(li);
            }
        });

        resultsContainer.style.display = found ? 'block' : 'none';
    });
</script>
