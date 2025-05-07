<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [AuthController::class, 'index']);
Route::get('login', [AuthController::class, 'index']);
Route::get('loginn', [AuthController::class, 'tologin']);
Route::get('register', [AuthController::class, 'toregister']);
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'registeruser']);
Route::get('logout', [AuthController::class, 'logout']);
Route::get('check-user', [HomeController::class, 'check_user']);

Route::middleware('admin')->prefix('admin')->group(
    function () {
        Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index']);

        Route::get('profile', [\App\Http\Controllers\Admin\ProfileController::class, 'index']);
        Route::post('profile/update', [\App\Http\Controllers\Admin\ProfileController::class, 'update']);

        Route::get('user/access/{id}', [\App\Http\Controllers\Admin\UserController::class, 'access']);
        Route::post('user-access/{id}', [\App\Http\Controllers\Admin\UserController::class, 'access_user']);
        Route::post('user-create', [\App\Http\Controllers\Admin\UserController::class, 'update_user']);
        Route::get('user/karyawan/{id}', [\App\Http\Controllers\Admin\UserController::class, 'karyawan']);

        Route::resource('akses-new', \App\Http\Controllers\Admin\AksesnewController::class);
        Route::get('akses-new/access/{id}', [\App\Http\Controllers\Admin\AksesnewController::class, 'access']);
        Route::resource('menu-fitur', \App\Http\Controllers\Admin\MenufiturController::class);

        Route::resource('karyawan', \App\Http\Controllers\Admin\KaryawanController::class);
        Route::resource('user', \App\Http\Controllers\Admin\UserController::class);
        Route::resource('departemen', \App\Http\Controllers\Admin\DepartemenController::class);
        Route::resource('pelanggan', \App\Http\Controllers\Admin\PelangganController::class);
        Route::resource('supplier', \App\Http\Controllers\Admin\SupplierController::class);
        Route::resource('barang', \App\Http\Controllers\Admin\BarangController::class);
        Route::resource('barangnonbesi', \App\Http\Controllers\Admin\BarangnonbesiController::class);
        Route::resource('pembelian', \App\Http\Controllers\Admin\PembelianController::class);
        Route::resource('popembelian', \App\Http\Controllers\Admin\PopembelianController::class);
        Route::resource('satuan', \App\Http\Controllers\Admin\SatuanController::class);
        Route::resource('merek', \App\Http\Controllers\Admin\MerekController::class);
        Route::resource('type', \App\Http\Controllers\Admin\TypeController::class);
        Route::resource('bagian', \App\Http\Controllers\Admin\BagianController::class);

        Route::get('type/merek/{id}', [\App\Http\Controllers\Admin\TypeController::class, 'merek']);
        Route::get('barang/get-types-by-merek/{merekId}', [\App\Http\Controllers\Admin\BarangController::class, 'getByMerek']);
        Route::get('barang/get-bagians/{id}', [\App\Http\Controllers\Admin\BarangController::class, 'getByBagian']);
        Route::get('barang/generate-kode-barang', [\App\Http\Controllers\Admin\BarangController::class, 'generateKodeBarangFromPrefix']);

        Route::resource('po-pembelian', \App\Http\Controllers\Admin\PopembelianController::class);
        Route::resource('pembelian', \App\Http\Controllers\Admin\PembelianController::class);
        Route::get('pembelian/supplier/{id}', [\App\Http\Controllers\Admin\PembelianController::class, 'supplier']);
        Route::get('pembelian/popembelian/{id}', [\App\Http\Controllers\Admin\PembelianController::class, 'popembelian']);
        Route::get('create-barang', [\App\Http\Controllers\Admin\PopembelianController::class, 'create_barang']);
        Route::post('add-barang', [\App\Http\Controllers\Admin\PopembelianController::class, 'add_barang']);
        Route::get('get-types-by-merek/{merekId}', [\App\Http\Controllers\Admin\PopembelianController::class, 'getByMerek']);
        Route::get('get-bagians/{id}', [\App\Http\Controllers\Admin\PopembelianController::class, 'getByBagian']);
        Route::get('generate-kode-barang', [\App\Http\Controllers\Admin\PopembelianController::class, 'generateKodeBarangFromPrefix']);


        Route::resource('pembelianpo', \App\Http\Controllers\Admin\PembelianpoController::class);
        Route::post('add_pembelian', [\App\Http\Controllers\Admin\PembelianpoController::class, 'add_pembelian']);

        Route::resource('inquery-popembelian', \App\Http\Controllers\Admin\InqueryPopembelianController::class);
        Route::resource('inquery-pembelian', \App\Http\Controllers\Admin\InqueryPembelianController::class);
        Route::get('inquery-popembelian/unpost/{id}', [\App\Http\Controllers\Admin\InqueryPopembelianController::class, 'unpost']);
        Route::get('inquery-popembelian/posting/{id}', [\App\Http\Controllers\Admin\InqueryPopembelianController::class, 'posting']);
        Route::get('hapuspo/{id}', [\App\Http\Controllers\Admin\InqueryPopembelianController::class, 'hapuspo'])->name('hapuspo');
        Route::get('inquery-pembelian/unpostpembelian/{id}', [\App\Http\Controllers\Admin\InqueryPembelianController::class, 'unpostpembelian']);
        Route::get('inquery-pembelian/postingpembelian/{id}', [\App\Http\Controllers\Admin\InqueryPembelianController::class, 'postingpembelian']);
        Route::get('hapuspembelian/{id}', [\App\Http\Controllers\Admin\InqueryPembelianController::class, 'hapuspembelian'])->name('hapuspembelian');
        Route::delete('inquery-pembelian/deletebarangs/{id}', [\App\Http\Controllers\Admin\InqueryPembelianController::class, 'deletebarangs']);
        Route::delete('inquery-popembelian/deletedetailpo/{id}', [\App\Http\Controllers\Admin\InqueryPopembelianController::class, 'deletedetailpo']);

        Route::get('laporan-pembelian', [\App\Http\Controllers\Admin\LaporanPembelianController::class, 'index']);
        Route::get('laporan-popembelian', [\App\Http\Controllers\Admin\LaporanPopembelianController::class, 'index']);
        Route::get('print-laporanpopembelian', [\App\Http\Controllers\Admin\LaporanPopembelianController::class, 'print_laporanpopembelian']);
        Route::get('print-laporanpembelian', [\App\Http\Controllers\Admin\LaporanPembelianController::class, 'print_laporanpembelian']);

        Route::get('popembelian/cetak-pdf/{id}', [\App\Http\Controllers\Admin\PopembelianController::class, 'cetakpdf']);
        Route::get('pembelian/cetak-pdf/{id}', [\App\Http\Controllers\Admin\PembelianController::class, 'cetakpdf']);

        Route::resource('pelunasan-pembelian', \App\Http\Controllers\Admin\PelunasanpembelianController::class);
        Route::resource('inquery-pelunasan-pembelian', \App\Http\Controllers\Admin\InqueryFakturpelunasanpembelianController::class);
        Route::get('pelunasan-pembelian/cetak-pdf/{id}', [\App\Http\Controllers\Admin\PelunasanpembelianController::class, 'cetakpdf']);
        Route::get('inquery-pelunasan-pembelian/unpost/{id}', [\App\Http\Controllers\Admin\InqueryFakturpelunasanpembelianController::class, 'unpost']);
        Route::get('inquery-pelunasan-pembelian/posting/{id}', [\App\Http\Controllers\Admin\InqueryFakturpelunasanpembelianController::class, 'posting']);
        Route::get('hapuspelunasanpembelian/{id}', [\App\Http\Controllers\Admin\InqueryFakturpelunasanpembelianController::class, 'hapuspelunasanpembelian'])->name('hapuspelunasanpembelian');

        Route::get('laporan-pelunasan-pembelian', [\App\Http\Controllers\Admin\LaporanPelunasanpembelianController::class, 'index']);
        Route::get('print-pelunasan-pembelian', [\App\Http\Controllers\Admin\LaporanPelunasanpembelianController::class, 'print_pelunasanpembelian']);

        Route::get('laporan-pelunasan-pembelian-global', [\App\Http\Controllers\Admin\LaporanPelunasanpembelianController::class, 'indexglobalpembelian']);
        Route::get('print-pelunasan-pembelian-global', [\App\Http\Controllers\Admin\LaporanPelunasanpembelianController::class, 'print_pelunasanglobalpembelian']);
    }
);