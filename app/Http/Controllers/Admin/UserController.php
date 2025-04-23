<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Dompdf\Dompdf;
use App\Models\User;
use App\Models\Karyawan;
use App\Models\Departemen;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Exports\RekapExport;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('cek_hapus', 'tidak')
            ->with('karyawan')
            ->whereHas('karyawan', function ($query) {
                $query->whereNotIn('departemen_id', [2]); // Menambahkan kondisi departemen_id selain 2 dan 3
            });

        if ($request->filled('keyword')) { // Gunakan filled() untuk memastikan keyword tidak kosong
            $keyword = $request->keyword;
            $query->where(function ($query) use ($keyword) {
                $query->whereHas('karyawan', function ($query) use ($keyword) {
                    $query->where('nama_lengkap', 'like', "%$keyword%");
                })
                    ->orWhere('kode_user', 'like', "%$keyword%");
            });
        }
        $users = $query->orderBy('created_at', 'desc')->get();
        // $users = $query->orderBy('created_at', 'desc')->paginate(10)->appends($request->query());

        return view('admin.user.index', compact('users'));
    }


    public function create()
    {

        $departemens = Departemen::all();
        $karyawans = Karyawan::where(['status' => 'null'])->get();
        return view('admin/user.create', compact('departemens', 'karyawans'));
    }

    public function karyawan($id)
    {
        $user = Karyawan::where('id', $id)->first();

        return json_decode($user);
    }

    public function edit($id)
    {

        $user = User::where('id', $id)->first();
        return view('admin/user.update', compact('user'));
    }

    public function access($id)
    {

        $user = User::where('id', $id)->first();
        return view('admin/user.access', compact('user'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'karyawan_id' => 'required',
            ],
            [
                'karyawan_id.required' => 'Pilih kode karyawan',
            ]
        );

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return back()->withInput()->with('error', $errors);
        }

        $number = mt_rand(1000000000, 9999999999);
        if ($this->qrcodeUserExists($number)) {
            $number = mt_rand(1000000000, 9999999999);
        }

        User::create(array_merge(
            $request->all(),
            [
                // 'menu' => '-',
                // 'password' => '-',
                'cek_hapus' => 'tidak',
                'kode_user' => $this->kode(),
                'qrcode_user' => $number,
                'tanggal_awal' => Carbon::now('Asia/Jakarta'),
                'password' => Hash::make('123456'),
                'menu' => [
                    'akses' => false,
                    'karyawan' => false,
                    'user' => false,
                    'departemen' => false,
                    'gaji karyawan' => false,
                    'supplier' => false,
                    'pelanggan' => false,
                    'merek' => false,
                    'kendaraan' => false,
                    'type karoseri' => false,
                    'barang' => false,
                    'po pembelian' => false,
                    'pembelian' => false,
                    'spk' => false,
                    'pengambilan bahan baku' => false,
                    'penjualan' => false,
                    'pelunasan' => false,
                    'deposit pemesanan' => false,
                    'inquery po pembelian' => false,
                    'inquery pembelian' => false,
                    'inquery spk' => false,
                    'inquery pengambilan bahan baku' => false,
                    'inquery deposit' => false,
                    'inquery penjualan' => false,
                    'inquery pelunasan' => false,
                    'laporan po pembelian' => false,
                    'laporan pembelian' => false,
                    'laporan spk' => false,
                    'laporan pengambilan bahan baku' => false,
                    'laporan deposit' => false,
                    'laporan penjualan' => false,
                    'laporan pelunasan' => false,
                    'perhitungan gaji' => false,
                    'memo hutang karyawan' => false,
                    'surat penawaran karoseri' => false,
                    'pelunasan pembelian' => false,
                    'pengambilan kas kecil' => false,
                    'saldo kas kecil' => false,
                    'inquery saldo kas kecil' => false,
                    'inquery pelunasan pembelian' => false,
                    'inquery surat penawaran' => false,
                    'inquery perhitungan gaji' => false,
                    'inquery memo hutang karyawan' => false,
                    'inquery pengambilan kas kecil' => false,
                ]
            ]
        ));

        Karyawan::where('id', $request->karyawan_id)->update([
            'status' => 'user',
        ]);

        return redirect('admin/user')->with('success', 'Berhasil mengubah User');
    }

    public function qrcodeUserExists($number)
    {
        return User::whereQrcodeUser($number)->exists();
    }

    public function cetakpdf($id)
    {
        $cetakpdf = User::where('id', $id)->first();
        $html = view('admin/user.cetak_pdf', compact('cetakpdf'));

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');

        $dompdf->render();

        $dompdf->stream();
    }

    public function kode()
    {
        // Cari karyawan terakhir dengan kode_karyawan yang diawali dengan 'AA'
        $lastBarang = User::where('kode_user', 'like', 'AB%')->latest()->first();
        if (!$lastBarang) {
            $num = 1;
        } else {
            $lastCode = $lastBarang->kode_user;
            $num = (int) substr($lastCode, strlen('AB')) + 1;
        }
        $formattedNum = sprintf("%06s", $num);
        $prefix = 'AB';
        $newCode = $prefix . $formattedNum;
        return $newCode;
    }

    public function access_user(Request $request)
    {

        $pelanggan = $request->pelanggan;

        if ($request->kendaraan) {
            $kendaraan = $request->kendaraan;
        } else {
            $kendaraan = 0;
        }

        if ($request->pelanggan) {
            $pelanggan = $request->pelanggan;
        } else {
            $pelanggan = 0;
        }

        User::where('id', $request->id)->update([
            // 'menu' => $menu,
            'menu' => [
                'kendaraan' => $kendaraan,
                'pelanggan' => $pelanggan
            ]
        ]);

        return redirect('admin/user')->with('success', 'Berhasil menambah Akses');
    }

    public function destroy($id)
    {

        $user = User::find($id);
        Karyawan::where('id', $user->karyawan_id)->update([
            'status' => 'null',
        ]);
        $user->delete();

        return redirect('admin/user')->with('success', 'Berhasil menghapus user');
    }

    // public function rekapexport()
    // {
    //     $user = User::all(); // Menggunakan all() langsung karena tidak perlu pengecekan keberadaan data

    //     if ($user->isEmpty()) {
    //         return redirect()->back()->withErrors(['error' => 'Data User tidak ditemukan']);
    //     }

    //     return Excel::download(new RekapExport($user), 'rekap_user.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    // }
}