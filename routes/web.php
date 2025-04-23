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
        Route::get('barang/get-bagians-by-merek/{merekId}', [\App\Http\Controllers\Admin\BarangController::class, 'getByBagian']);
        Route::get('barang/generate-kode-barang', [\App\Http\Controllers\Admin\BarangController::class, 'generateKodeBarangFromPrefix']);


        Route::resource('inquery-popembelian', \App\Http\Controllers\Admin\InqueryPopembelianController::class);
        Route::resource('inquery-pembelian', \App\Http\Controllers\Admin\InqueryPembelianController::class);

        Route::get('laporan-pembelian', [\App\Http\Controllers\Admin\LaporanPembelianController::class, 'index']);
        Route::get('laporan-popembelian', [\App\Http\Controllers\Admin\LaporanPopembelianController::class, 'index']);
        Route::get('print-laporanpopembelian', [\App\Http\Controllers\Admin\LaporanPopembelianController::class, 'print_laporanpopembelian']);
        Route::get('print-laporanpembelian', [\App\Http\Controllers\Admin\LaporanPembelianController::class, 'print_laporanpembelian']);
    }
);