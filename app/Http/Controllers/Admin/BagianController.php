<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Bagian;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Merek;
use Illuminate\Support\Facades\Validator;

class BagianController extends Controller
{
    public function index()
    {
        $bagians = Bagian::get();
        return view('admin/bagian.index', compact('bagians'));
    }

    public function create()
    {
        $mereks = Merek::get();
        return view('admin/bagian.create', compact('mereks'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'merek_id' => 'required',
                'nama_bagian' => 'required',
            ],
            [
                'merek_id.required' => 'Pilih Merek',
                'nama_bagian.required' => 'Masukkan nama bagian',
            ]
        );

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        $kode = $this->kode();

        Bagian::create(array_merge(
            $request->all(),
            [
                'kode_bagian' => $this->kode(),
                'merek_id' => $request->merek_id,
                'qrcode_bagian' => 'https://javateknik.co.id/bagian/' . $kode,
                'tanggal_awal' => Carbon::now('Asia/Jakarta'),
            ],
        ));

        return redirect('admin/bagian')->with('success', 'Berhasil menambahkan bagian');
    }

    public function kode()
    {
        // Ambil kode terakhir dengan format AD#####A
        $lastMerek = Bagian::where('kode_bagian', 'like', 'AD_____' . 'C') // 5 underscores = 5 digit angka
            ->orderByDesc('kode_bagian')
            ->first();

        if ($lastMerek) {
            // Ambil angka dari posisi ke-3 sepanjang 5 karakter
            $lastNumber = (int) substr($lastMerek->kode_bagian, 2, 5);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        // Format angka menjadi 5 digit, tambahkan prefix 'AD' dan suffix 'C'
        $kode_bagian = 'AD' . str_pad($newNumber, 5, '0', STR_PAD_LEFT) . 'C';

        return $kode_bagian;
    }

    public function edit($id)
    {
        $mereks = Merek::get();
        $bagian = Bagian::where('id', $id)->first();
        return view('admin/bagian.update', compact('bagian', 'mereks'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'merek_id' => 'required',
                'nama_bagian' => 'required',
            ],
            [
                'merek_id.required' => 'Pilih Merek',
                'nama_bagian.required' => 'Masukkan nama bagian',
            ]
        );

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        Bagian::where('id', $id)->update([
            'merek_id' => $request->merek_id,
            'nama_bagian' => $request->nama_bagian,
        ]);

        return redirect('admin/bagian')->with('success', 'Berhasil memperbarui Bagian');
    }

    public function cetakqrcode($id)
    {
        $bagians = Bagian::find($id);
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('admin.bagian.cetak_pdf', compact('bagians'));
        $pdf->setPaper('letter', 'portrait');
        return $pdf->stream('QrCodeMerek.pdf');
    }

    public function destroy($id)
    {
        $bagian = Bagian::find($id);
        $bagian->delete();

        return redirect('admin/bagian')->with('success', 'Berhasil menghapus Bagian');
    }
}