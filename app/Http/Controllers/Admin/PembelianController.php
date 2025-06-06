<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Supplier;
use App\Models\Sparepart;
use Illuminate\Http\Request;
use App\Models\Pembelian_ban;
use App\Models\Pembelian_part;
use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Detail_barang;
use App\Models\Detail_pembelian;
use App\Models\Detail_pembelianpart;
use App\Models\Detailpembelian;
use App\Models\Pembelian;
use App\Models\Popembelian;
use App\Models\Satuan;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class PembelianController extends Controller
{
    public function index()
    {

            $pembelian_parts = Pembelian::all();
            $suppliers = Supplier::all();
            $barangs = Barang::all();
            $satuans = Satuan::all();
            $popembelians = Popembelian::all();

            return view('admin.pembelian.index', compact('popembelians', 'satuans', 'pembelian_parts', 'suppliers', 'barangs'));

    }

    public function indexnon()
    {

            $pembelian_parts = Pembelian::all();
            $suppliers = Supplier::all();
            $barangs = Barang::all();
            $satuans = Satuan::all();

            return view('admin.pembelian.indexnon', compact('satuans', 'pembelian_parts', 'suppliers', 'barangs'));

    }

    public function popembelian($id)
    {
        $popembelian = Popembelian::where('id', $id)->with('supplier')->first();

        return json_decode($popembelian);
    }

    public function tabelpart()
    {
        $spareparts = Barang::all();
        return response()->json($spareparts);
    }


    public function tambah_supplier(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_supp' => 'required',
                'alamat' => 'required',

            ],
            [
                'nama_supp.required' => 'Masukkan nama supplier',
                'alamat.required' => 'Masukkan Alamat',

            ]
        );

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }


        $kode_supp = $this->kode_supp();

        Supplier::create(array_merge(
            $request->all(),
            [
                'kode_supplier' => $this->kode_supp(),
                'qrcode_supplier' => 'https://tigerload.id/supplier/' . $kode_supp,
                'tanggal_awal' => Carbon::now('Asia/Jakarta'),
                // 'qrcode_supplier' => 'http://192.168.1.46/javaline/supplier/' . $kode
            ]
        ));

        return Redirect::back()->with('success', 'Berhasil menambahkan supplier');
    }

    public function kode_supp()
    {
        $supplier = Supplier::all();
        if ($supplier->isEmpty()) {
            $num = "000001";
        } else {
            $id = Supplier::getId();
            foreach ($id as $value);
            $idlm = $value->id;
            $idbr = $idlm + 1;
            $num = sprintf("%06s", $idbr);
        }

        $data = 'AC';
        $kode_supplier = $data . $num;
        return $kode_supplier;
    }



    public function store(Request $request)
    {
        $validasi_pelanggan = Validator::make(
            $request->all(),
            [
                'supplier_id' => 'required',

            ],
            [
                'supplier_id.required' => 'Pilih supplier',

            ]
        );

        $error_pelanggans = array();

        if ($validasi_pelanggan->fails()) {
            array_push($error_pelanggans, $validasi_pelanggan->errors()->all()[0]);
        }

        $error_pesanans = array();
        $data_pembelians = collect();

        if ($request->has('barang_id')) {
            for ($i = 0; $i < count($request->barang_id); $i++) {
                $validasi_produk = Validator::make($request->all(), [
                    'barang_id.' . $i => 'required',
                    'kode_barang.' . $i => 'required',
                    'nama_barang.' . $i => 'required',
                    'satuan_id.' . $i => 'required',
                    'jumlah.' . $i => 'required',
                    'harga.' . $i => 'required',
                    'harga_jual.' . $i => 'required',
                    // 'diskon.' . $i => 'required',
                    'total.' . $i => 'required',
                ]);

                if ($validasi_produk->fails()) {
                    array_push($error_pesanans, "Barang nomor " . $i + 1 . " belum dilengkapi!");
                }


                $barang_id = is_null($request->barang_id[$i]) ? '' : $request->barang_id[$i];
                $kode_barang = is_null($request->kode_barang[$i]) ? '' : $request->kode_barang[$i];
                $nama_barang = is_null($request->nama_barang[$i]) ? '' : $request->nama_barang[$i];
                $satuan_id = is_null($request->satuan_id[$i]) ? '' : $request->satuan_id[$i];
                $jumlah = is_null($request->jumlah[$i]) ? '' : $request->jumlah[$i];
                $harga = is_null($request->harga[$i]) ? '' : $request->harga[$i];
                $harga_jual = is_null($request->harga_jual[$i]) ? '' : $request->harga_jual[$i];
                $diskon = is_null($request->diskon[$i]) ? '' : $request->diskon[$i];
                $total = is_null($request->total[$i]) ? '' : $request->total[$i];

                $data_pembelians->push([
                    'barang_id' => $barang_id,
                    'kode_barang' => $kode_barang,
                    'nama_barang' => $nama_barang,
                    'satuan_id' => $satuan_id,
                    'jumlah' => $jumlah,
                    'harga' => $harga,
                    'harga_jual' => $harga_jual,
                    'diskon' => $diskon,
                    'total' => $total
                ]);
            }
        } else {
        }

        if ($error_pelanggans || $error_pesanans) {
            return back()
                ->withInput()
                ->with('error_pelanggans', $error_pelanggans)
                ->with('error_pesanans', $error_pesanans)
                ->with('data_pembelians', $data_pembelians);
        }

        // format tanggal indo
        $tanggal1 = Carbon::now('Asia/Jakarta');
        $format_tanggal = $tanggal1->format('d F Y');

        $tanggal = Carbon::now()->format('Y-m-d');
        $transaksi = Pembelian::create([
            'kode_pembelian' => $this->kode(),
            'kategori' => $request->kategori,
            'supplier_id' => $request->supplier_id,
            'tanggal' => $format_tanggal,
            'tanggal_awal' => $tanggal,
            'grand_total' => str_replace(',', '.', str_replace('.', '', $request->grand_total)),
            'status' => 'posting',
            'status_notif' => false,
        ]);

        $transaksi_id = $transaksi->id;

        if ($transaksi) {
            foreach ($data_pembelians as $data_pesanan) {
                // Create a new Detailpembelian
                $detailPembelian = Detailpembelian::create([
                    'pembelian_id' => $transaksi->id,
                    'barang_id' => $data_pesanan['barang_id'],
                    'kode_barang' => $data_pesanan['kode_barang'],
                    'nama_barang' => $data_pesanan['nama_barang'],
                    'satuan_id' => $data_pesanan['satuan_id'],
                    'jumlah' => $data_pesanan['jumlah'],
                    'harga' => str_replace('.', '', $data_pesanan['harga']),
                    'harga_jual' => str_replace('.', '', $data_pesanan['harga_jual']),
                    'diskon' => str_replace('.', '', $data_pesanan['diskon']),
                    'total' => str_replace('.', '', $data_pesanan['total']),
                ]);
            }
        }


        $pembelians = Pembelian::find($transaksi_id);

        $parts = Detailpembelian::where('pembelian_id', $pembelians->id)->get();

        return view('admin.pembelian.show', compact('parts', 'pembelians'));
    }

    public function kode()
    {
        $pembelian_part = Pembelian::all();
        if ($pembelian_part->isEmpty()) {
            $num = "000001";
        } else {
            $id = Pembelian::getId();
            foreach ($id as $value);
            $idlm = $value->id;
            $idbr = $idlm + 1;
            $num = sprintf("%06s", $idbr);
        }

        $data = 'FP';
        $kode_pembelian_part = $data . $num;
        return $kode_pembelian_part;
    }

    public function show($id)
    {

            $pembelian_part = Pembelian::find($id);
            $parts = Detailpembelian::where('pembelian_part_id', $pembelian_part->id)->get();


            $pembelians = Pembelian::where('id', $id)->first();

            return view('admin.pembelian.show', compact('parts', 'pembelians'));

    }

    public function cetakpdf($id)
    {

            $pembelians = Pembelian::find($id);
            $parts = Detailpembelian::where('pembelian_id', $pembelians->id)->get();

            // Load the view and set the paper size to portrait letter
            $pembelianbans = Pembelian::where('id', $id)->first();
            $pdf = PDF::loadView('admin.pembelian.cetak_pdf', compact('parts', 'pembelians', 'pembelianbans'));
            $pdf->setPaper('letter', 'portrait'); // Set the paper size to portrait letter

            return $pdf->stream('Faktur_Pembelian.pdf');

    }
}