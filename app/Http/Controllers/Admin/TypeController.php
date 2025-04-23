<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Type;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Merek;
use Illuminate\Support\Facades\Validator;

class TypeController extends Controller
{
    public function index()
    {
        $types = Type::get();
        return view('admin/type.index', compact('types'));
    }

    public function merek($id)
    {
        $merek = Merek::where('id', $id)->first();

        return json_decode($merek);
    }

    public function create()
    {
        $mereks = Merek::get();
        return view('admin/type.create', compact('mereks'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'merek_id' => 'required',
                'nama_type' => 'required',
            ],
            [
                'merek_id.required' => 'Pilih Merek',
                'nama_type.required' => 'Masukkan nama type',
            ]
        );

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        $kode = $this->kode();

        Type::create(array_merge(
            $request->all(),
            [
                'kode_type' => $this->kode(),
                'merek_id' => $request->merek_id,
                'qrcode_type' => 'https://javateknik.co.id/type/' . $kode,
                'tanggal_awal' => Carbon::now('Asia/Jakarta'),
            ],
        ));

        return redirect('admin/type')->with('success', 'Berhasil menambahkan type');
    }

    public function kode()
    {
        // Ambil kode terakhir dengan format AD#####A
        $lastMerek = Type::where('kode_type', 'like', 'AD_____' . 'B') // 5 underscores = 5 digit angka
            ->orderByDesc('kode_type')
            ->first();

        if ($lastMerek) {
            // Ambil angka dari posisi ke-3 sepanjang 5 karakter
            $lastNumber = (int) substr($lastMerek->kode_type, 2, 5);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        // Format angka menjadi 5 digit, tambahkan prefix 'AD' dan suffix 'B'
        $kode_type = 'AD' . str_pad($newNumber, 5, '0', STR_PAD_LEFT) . 'B';

        return $kode_type;
    }

    public function edit($id)
    {
        $mereks = Merek::get();
        $type = Type::where('id', $id)->first();
        return view('admin/type.update', compact('type', 'mereks'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'merek_id' => 'required',
                'nama_type' => 'required',
            ],
            [
                'merek_id.required' => 'Pilih Merek',
                'nama_type.required' => 'Masukkan nama type',
            ]
        );

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        Type::where('id', $id)->update([
            'merek_id' => $request->merek_id,
            'nama_type' => $request->nama_type,
        ]);

        return redirect('admin/type')->with('success', 'Berhasil memperbarui Type');
    }

    public function cetakqrcode($id)
    {
        $types = Type::find($id);
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('admin.type.cetak_pdf', compact('types'));
        $pdf->setPaper('letter', 'portrait');
        return $pdf->stream('QrCodeMerek.pdf');
    }

    public function destroy($id)
    {
        $type = Type::find($id);
        $type->delete();

        return redirect('admin/type')->with('success', 'Berhasil menghapus Type');
    }
}