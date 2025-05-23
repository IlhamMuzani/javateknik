<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Golongan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Dompdf\Dompdf;

class GolonganController extends Controller
{
    public function index()
    {

        $golongans = Golongan::all();
        return view('admin/golongan.index', compact('golongans'));
    }

    public function create()
    {

        return view('admin/golongan.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_golongan' => 'required',
            ],
            [
                'nama_golongan.required' => 'Masukkan nama golongan',
            ]
        );
        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }
        $kode = $this->kode();

        $tanggal = Carbon::now('Asia/Jakarta');
        $format_tanggal = $tanggal->format('d F Y');

        Golongan::create(array_merge(
            $request->all(),
            [
                'kode_golongan' => $kode,
                'qrcode_golongan' => 'http://javaline.id/golongan/' . $kode,
                'tanggal_awal' => $format_tanggal,
            ],
        ));

        return redirect('admin/golongan')->with('success', 'Berhasil menambahkan golongan');
    }

    public function kode()
    {
        $lastBarang = Golongan::latest()->first();
        if (!$lastBarang) {
            $num = 1;
        } else {
            $lastCode = $lastBarang->kode_golongan;
            $num = (int) substr($lastCode, strlen('AE')) + 1;
        }
        $formattedNum = sprintf("%06s", $num);
        $prefix = 'AE';
        $newCode = $prefix . $formattedNum;
        return $newCode;
    }

    public function edit($id)
    {


        $golongan = Golongan::where('id', $id)->first();
        return view('admin/golongan.update', compact('golongan'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_golongan' => 'required',
        ], [
            'nama_golongan.required' => 'Nama golongan tidak boleh Kosong',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }
        $golongan = Golongan::find($id);
        if (!$golongan) {
            return back()->with('error', 'Golongan tidak ditemukan');
        }
        $tanggal = Carbon::now('Asia/Jakarta');
        $golongan->nama_golongan = $request->nama_golongan;
        $golongan->tanggal_awal = $tanggal;
        $golongan->save();

        return redirect('admin/golongan')->with('success', 'Berhasil memperbarui Golongan');
    }

    public function cetakpdf($id)
    {
        $cetakpdf = Golongan::where('id', $id)->first();
        $html = view('admin/golongan.cetak_pdf', compact('cetakpdf'));
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream();
    }

    public function destroy($id)
    {
        $golongan = Golongan::find($id);
        $golongan->delete();
        return redirect('admin/golongan')->with('success', 'Berhasil menghapus Golongan');
    }
}
