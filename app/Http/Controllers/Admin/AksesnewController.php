<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Karyawan;
use App\Models\Departemen;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Menufitur;
use Illuminate\Support\Facades\Auth;

class AksesnewController extends Controller
{

    public function index()
    {
        $drivers = Karyawan::whereIn('departemen_id', [1, 4])
            ->select('id')
            ->get();

        $aksess = User::where('cek_hapus', 'tidak')
            ->whereIn('karyawan_id', $drivers->pluck('id'))
            ->get();

        return view('admin.akses_new.index', compact('aksess'));
    }

    // public function edit($id)
    // {
    //     // Ambil user berdasarkan ID dari URL, bukan dari user yang login
    //     $user = User::findOrFail($id);

    //     // Ambil semua menu yang tersedia
    //     $menufiturs = Menufitur::all();

    //     // Ambil menu yang sudah dipilih oleh user yang sedang diedit
    //     $userMenuIds = $user->menufiturs->pluck('id')->toArray();

    //     return view('admin.akses_new.update', compact('menufiturs', 'userMenuIds', 'user'));
    // }

    // public function update(Request $request, $id)
    // {
    //     $user = User::findOrFail($id);
    //     // Update akses menu untuk user
    //     $user->menufiturs()->sync($request->menu_ids);

    //     return redirect()->back()->with('success', 'Akses menu berhasil diperbarui');
    //     // return redirect('admin/user')->with('success', 'Berhasil mengubah akses');

    // }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $menufiturs = Menufitur::all()->groupBy('kategori'); // Mengelompokkan menu berdasarkan kategori

        // Ambil izin akses user dari tabel pivot
        $userMenu = $user->menufiturs->keyBy('id');

        return view('admin.akses_new.update', compact('menufiturs', 'userMenu', 'user'));
    }


    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Ambil daftar menu yang dicentang
        $selectedMenus = $request->input('menus', []);
        $permissions = $request->input('permissions', []);

        // Reset semua izin sebelum menambah yang baru
        $user->menufiturs()->detach();

        // Loop melalui semua menu yang dicentang
        foreach ($selectedMenus as $menuId => $menuValue) {
            if ($menuValue == 0) {
                continue; // Jika menu tidak dicentang, skip
            }

            $permission = $permissions[$menuId] ?? [];

            // Simpan menu meskipun tidak ada izin yang dicentang
            $user->menufiturs()->attach($menuId, [
                'can_create' => isset($permission['create']) ? 1 : 0,
                'can_update' => isset($permission['update']) ? 1 : 0,
                'can_delete' => isset($permission['delete']) ? 1 : 0,
                'can_show' => isset($permission['show']) ? 1 : 0,
                'can_posting' => isset($permission['posting']) ? 1 : 0,
                'can_unpost' => isset($permission['unpost']) ? 1 : 0,
                'can_posting_all' => isset($permission['posting_all']) ? 1 : 0,
                'can_unpost_all' => isset($permission['unpost_all']) ? 1 : 0,
                'can_search' => isset($permission['search']) ? 1 : 0,
                'can_print' => isset($permission['print']) ? 1 : 0,
            ]);
        }

        return redirect('admin/akses-new')->with('success', 'Berhasil mengubah Akses Menu' . ' ' . $user->karyawan->nama_lengkap ?? null);
    }
}
