<?php

namespace App\Http\Controllers\admin;

use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Golongan;
use App\Models\Harga;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class HargaController extends Controller
{
    public function index(Request $request)
    {
        $barangs = Barang::all();

        if ($request->has('keyword')) {
            $keyword = $request->keyword;
            $hargas = Harga::where('kode_harga', 'like', "%$keyword%")
                ->orWhere('barang_id', 'like', "%$keyword%")
                ->orderBy('created_at', 'desc')
                ->paginate(10)
                ->appends($request->only('keyword', 'status'));
        } else {
            $hargas = Harga::orderBy('created_at', 'desc')
                ->paginate(10)
                ->appends($request->only('keyword', 'status'));
        }

        return view('admin.harga.index', compact('hargas','barangs'));
    }

    public function create()
    {

        $harga = Harga::all();
        $barangs = Barang::all();
        return view('admin/harga.create', compact('harga', 'barangs'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'barang_id' => 'required',
            ],
            [
                'barang_id.required' => 'Masukkan barang',
            ]
        );

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        $kode = $this->kode();
        // $nama_rute_uppercase = strtoupper($request->barang_id);
        Harga::create(array_merge(
            $request->all(),
            [
                'kode_harga' => $this->kode(),
                'qrcode_harga' => 'https://javateknik.id/harga/' . $kode,
                // 'kategori' =>  $request->kategori,
                'barang_id' =>  $request->barang_id,
                'tanggal_awal' => Carbon::now('Asia/Jakarta'),
                'harga_a' => $request->harga_a ? str_replace('.', '', $request->harga_a) : null,
                'harga_b' => $request->harga_b ? str_replace('.', '', $request->harga_b) : null,
                'harga_c' => $request->harga_c ? str_replace('.', '', $request->harga_c) : null,
                'harga_d' => $request->harga_d ? str_replace('.', '', $request->harga_d) : null,
                'harga_e' => $request->harga_e ? str_replace('.', '', $request->harga_e) : null,
            ],
        ));

        return redirect('admin/harga')->with('success', 'Berhasil menambahkan harga');
    }

    public function barang($id)
    {
        $barang = Barang::where('id', $id)->first();

        return json_decode($barang);
    }

    public function kode()
    {
        $lastBarang = Harga::latest()->first();
        if (!$lastBarang) {
            $num = 1;
        } else {
            $lastCode = $lastBarang->kode_harga;
            $num = (int) substr($lastCode, strlen('AH')) + 1;
        }
        $formattedNum = sprintf("%06s", $num);
        $prefix = 'AH';
        $newCode = $prefix . $formattedNum;
        return $newCode;
    }

    public function edit($id)
    {
        $harga = Harga::where('id', $id)->first();

        return view('admin/harga.update', compact('harga'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'barang_id' => 'required',
            ],
            [
                'barang_id.required' => 'Masukkan barang',
            ]
        );

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }
        $nama_rute_uppercase = strtoupper($request->barang_id);
        $harga = Harga::findOrFail($id);

        $harga->barang_id = $request->barang_id;
        $harga->harga_a = $request->harga_a ? str_replace('.', '', $request->harga_a) : null;
        $harga->harga_b = $request->harga_b ? str_replace('.', '', $request->harga_b) : null;
        $harga->harga_c = $request->harga_c ? str_replace('.', '', $request->harga_c) : null;
        $harga->harga_d = $request->harga_d ? str_replace('.', '', $request->harga_d) : null;
        $harga->harga_e = $request->harga_e ? str_replace('.', '', $request->harga_e) : null;


        $harga->save();

        return back()->with('success', 'Berhasil memperbarui harga');
    }


    public function destroy($id)
    {
        $harga = Harga::find($id);
        $harga->delete();

        return redirect('admin/harga')->with('success', 'Berhasil menghapus harga');
    }
}