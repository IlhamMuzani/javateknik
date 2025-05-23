<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Bagian;
use App\Models\Barang;
use App\Models\Merek;
use App\Models\Satuan;
use App\Models\Type;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::query(); // Ambil semua kolom

        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');
            $query->where(function ($q) use ($keyword) {
                $q->where('kode_barang', 'like', "%$keyword%");
            });
        }

        // Ambil semua data tanpa pagination
        $barangs = $query->orderBy('created_at', 'desc')->get();

        return view('admin.barang.index', compact('barangs'));
    }


    public function getByMerek($merekId)
    {
        $types = Type::where('merek_id', $merekId)->get();

        return response()->json($types);
    }

    public function getByBagian($id)
    {
        $bagians = Bagian::where('id', $id)->first();

        return response()->json($bagians);
    }

    public function create()
    {
        $mereks = Merek::get();
        $types = Type::get();
        $bagians = Bagian::get();
        $satuans = Satuan::get();

        $lastBarang = Barang::orderBy('kode_barang', 'desc')->first();
        if ($lastBarang) {
            $lastKodeBarang = $lastBarang->kode_barang;
            $lastNumber = (int)substr($lastKodeBarang, 3); // Ambil angka setelah prefix
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001'; // Jika belum ada data, mulai dari 0001
        }
        return view('admin/barang.create', compact('satuans', 'mereks', 'types', 'bagians', 'newNumber'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'merek_id' => 'required',
                'type_id' => 'required',
                'bagian_id' => 'required',
                'nama_barang' => 'required',
                'satuan_id' => 'required',
                // 'keterangan' => 'required',
                'harga_barang' => 'required',
            ],
            [
                'merek_id.required' => 'Pilih Merek',
                'type_id.required' => 'Pilih Type',
                'bagian_id.required' => 'Pilih Bagian',
                'nama_barang.required' => 'Masukkan nama barang',
                'satuan_id.required' => 'Pilih Satuan',
                // 'keterangan.required' => 'Masukkan keterangan',
                'harga_barang.required' => 'Masukkan harga_barang',
            ]
        );

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return back()->withInput()->with('error', $errors);
        }


        $tanggal = Carbon::now()->format('Y-m-d');
        Barang::create(array_merge(
            $request->all(),
            [
                'kode_last' => $request->kode_last,
                'kode_barang' => $request->kode_barang,
                'harga_barang' => str_replace(',', '.', str_replace('.', '', $request->harga_barang)),
                'qrcode_barang' => 'https://javateknik.co.id/barang/' . $request->kode_barang,
                'tanggal_awal' => $tanggal,

            ]
        ));

        return redirect('admin/barang')->with('success', 'Berhasil menambahkan barang');
    }

    public function edit($id)
    {

        $mereks = Merek::get();
        $types = Type::get();
        $bagians = Bagian::get();
        $satuans = Satuan::get();

        $barang = Barang::where('id', $id)->first();
        return view('admin/barang.update', compact('barang', 'mereks', 'types', 'bagians', 'satuans'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'merek_id' => 'required',
                'type_id' => 'required',
                'bagian_id' => 'required',
                'nama_barang' => 'required',
                // 'jumlah' => 'required',
                'satuan_id' => 'required',
                // 'keterangan' => 'required',
                'harga_barang' => 'required',
            ],
            [
                'merek_id.required' => 'Pilih Merek',
                'type_id.required' => 'Pilih Type',
                'bagian_id.required' => 'Pilih Bagian',
                'nama_barang.required' => 'Masukkan nama barang',
                // 'jumlah.required' => 'Masukkan ukuran',
                'satuan_id.required' => 'Pilih Satuan',
                // 'keterangan.required' => 'Masukkan keterangan',
                'harga_barang.required' => 'Masukkan harga_barang',
            ]
        );

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        $barang = Barang::findOrFail($id);

        Barang::where('id', $id)->update([
            'merek_id' => $request->merek_id,
            'type_id' => $request->type_id,
            'bagian_id' => $request->bagian_id,
            'nama_barang' => $request->nama_barang,
            'jumlah' => $request->jumlah,
            'satuan_id' => $request->satuan_id,
            'harga_barang' => str_replace(',', '.', str_replace('.', '', $request->harga_barang)),
            'keterangan' => $request->keterangan,
        ]);

        return redirect('admin/barang')->with('success', 'Berhasil memperbarui barang');
    }


    public function cetakqrcode($id)
    {
        $barangs = Barang::find($id);
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('admin.barang.cetak_pdf', compact('barangs'));
        $pdf->setPaper('letter', 'portrait');
        return $pdf->stream('QrCodeBarang.pdf');
    }

    public function show($id)
    {


        $barang = Barang::where('id', $id)->first();
        return view('admin/barang.show', compact('barang'));
    }


    public function destroy($id)
    {
        $tipe = Barang::find($id);
        $tipe->delete();

        return redirect('admin/barang')->with('success', 'Berhasil menghapus barang');
    }
}