<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Dompdf\Dompdf;
use App\Models\Merek;
use App\Models\Ukuran;
use App\Models\Kendaraan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Modelken;
use App\Models\Tipe;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Mockery\Matcher\Type;

class MerekController extends Controller
{
    public function index()
    {
        $mereks = Merek::get();
        return view('admin/merek.index', compact('mereks'));
    }

    public function create()
    {
        return view('admin/merek.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_merek' => 'required',
            ],
            [
                'nama_merek.required' => 'Masukkan nama merek',
            ]
        );

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        $kode = $this->kode();

        Merek::create(array_merge(
            $request->all(),
            [
                'kode_merek' => $this->kode(),
                'qrcode_merek' => 'https://javateknik.co.id/merek/' . $kode,
                'tanggal_awal' => Carbon::now('Asia/Jakarta'),
            ],
        ));

        return redirect('admin/merek')->with('success', 'Berhasil menambahkan merek');
    }

    public function kode()
    {
        // Ambil kode terakhir dengan format AD#####A
        $lastMerek = Merek::where('kode_merek', 'like', 'AD_____' . 'A') // 5 underscores = 5 digit angka
            ->orderByDesc('kode_merek')
            ->first();

        if ($lastMerek) {
            // Ambil angka dari posisi ke-3 sepanjang 5 karakter
            $lastNumber = (int) substr($lastMerek->kode_merek, 2, 5);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        // Format angka menjadi 5 digit, tambahkan prefix 'AD' dan suffix 'A'
        $kode_merek = 'AD' . str_pad($newNumber, 5, '0', STR_PAD_LEFT) . 'A';

        return $kode_merek;
    }

    public function edit($id)
    {

        $merek = Merek::where('id', $id)->first();
        return view('admin/merek.update', compact('merek'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_merek' => 'required',
            ],
            [
                'nama_merek.required' => 'Masukkan nama merek',
            ]
        );

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        Merek::where('id', $id)->update([
            'nama_merek' => $request->nama_merek,
        ]);

        return redirect('admin/merek')->with('success', 'Berhasil memperbarui Merek');
    }

    public function cetakqrcode($id)
    {
        $mereks = Merek::find($id);
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('admin.merek.cetak_pdf', compact('mereks'));
        $pdf->setPaper('letter', 'portrait');
        return $pdf->stream('QrCodeMerek.pdf');
    }

    public function destroy($id)
    {
        $merek = Merek::find($id);
        $merek->delete();

        return redirect('admin/merek')->with('success', 'Berhasil menghapus Merek');
    }
}
