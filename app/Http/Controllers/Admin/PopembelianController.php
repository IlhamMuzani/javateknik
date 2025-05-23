<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Bagian;
use App\Models\Barang;
use App\Models\Detailpembelian;
use App\Models\Detailpopembelian;
use App\Models\Merek;
use App\Models\Pembelian;
use App\Models\Popembelian;
use App\Models\Satuan;
use App\Models\Type;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class PopembelianController extends Controller
{
    public function index()
    {

        $poPembelians = Popembelian::all();
        $suppliers = Supplier::all();
        $barangs = Barang::all();
        $satuans = Satuan::all();
        return view('admin.popembelian.index', compact('satuans', 'poPembelians', 'suppliers', 'barangs'));
    }

    public function create_barang()
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
        return view('admin/popembelian.create_barang', compact('satuans', 'mereks', 'types', 'bagians', 'newNumber'));
    }

    public function add_barang(Request $request)
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
                'qrcode_barang' => 'https://javateknik.co.id/barang/' . $request->kode_barang,
                'tanggal_awal' => $tanggal,

            ]
        ));

        return redirect('admin/po-pembelian')->with('success', 'Berhasil menambahkan barang');
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

    public function store(Request $request)
    {
        $validasi_pelanggan = Validator::make(
            $request->all(),
            [
                'supplier_id' => 'required',
            ],
            [
                'supplier_id.required' => 'Pilih nama supplier',
            ]
        );

        $error_pelanggans = array();
        $error_pesanans = array();
        $data_pembelians = collect();

        if ($validasi_pelanggan->fails()) {
            array_push($error_pelanggans, $validasi_pelanggan->errors()->all()[0]);
        }

        if ($request->has('barang_id')) {
            for ($i = 0; $i < count($request->barang_id); $i++) {
                $validasi_produk = Validator::make($request->all(), [
                    'barang_id.' . $i => 'required',
                    'kode_barang.' . $i => 'required',
                    'nama_barang.' . $i => 'required',
                    'harga.' . $i => 'required',
                    'jumlah.' . $i => 'required',
                    'satuan_id.' . $i => 'required',
                    'total.' . $i => 'required',
                ]);

                if ($validasi_produk->fails()) {
                    array_push($error_pesanans, "Barang nomor " . $i + 1 . " belum dilengkapi!");
                }


                $barang_id = is_null($request->barang_id[$i]) ? '' : $request->barang_id[$i];
                $kode_barang = is_null($request->kode_barang[$i]) ? '' : $request->kode_barang[$i];
                $nama_barang = is_null($request->nama_barang[$i]) ? '' : $request->nama_barang[$i];
                $harga = is_null($request->harga[$i]) ? '' : $request->harga[$i];
                $jumlah = is_null($request->jumlah[$i]) ? '' : $request->jumlah[$i];
                $satuan_id = is_null($request->satuan_id[$i]) ? '' : $request->satuan_id[$i];
                $total = is_null($request->total[$i]) ? '' : $request->total[$i];

                $data_pembelians->push([
                    'barang_id' => $barang_id,
                    'kode_barang' => $kode_barang,
                    'nama_barang' => $nama_barang,
                    'harga' => $harga,
                    'jumlah' => $jumlah,
                    'satuan_id' => $satuan_id,
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
        $kode = $this->kode();

        $tanggal = Carbon::now()->format('Y-m-d');
        $transaksi = Popembelian::create([
            'kode_po_pembelian' => $this->kode(),
            'supplier_id' => $request->supplier_id,
            'qrcode_popembelian' => 'https://javateknik.id/popembelian/' . $kode,
            'tanggal' => $format_tanggal,
            'tanggal_awal' => $tanggal,
            'status' => 'posting',
            'status_notif' => false,
        ]);

        $transaksi_id = $transaksi->id;

        if ($transaksi) {
            foreach ($data_pembelians as $data_pembelian) {
                Detailpopembelian::create([
                    'popembelian_id' => $transaksi->id,
                    'barang_id' => $data_pembelian['barang_id'],
                    'kode_barang' => $data_pembelian['kode_barang'],
                    'nama_barang' => $data_pembelian['nama_barang'],
                    'jumlah' => $data_pembelian['jumlah'],
                    'satuan_id' => $data_pembelian['satuan_id'],
                    'harga' => str_replace('.', '', $data_pembelian['harga']),
                    'total' => str_replace('.', '', $data_pembelian['total']),

                ]);
            }
        }

        $pembelians = Popembelian::find($transaksi_id);

        $parts = Detailpopembelian::where('popembelian_id', $pembelians->id)->get();

        return view('admin.popembelian.show', compact('parts', 'pembelians'));
    }

    public function kode()
    {
        $pembelian_part = Popembelian::all();
        if ($pembelian_part->isEmpty()) {
            $num = "000001";
        } else {
            $id = Popembelian::getId();
            foreach ($id as $value);
            $idlm = $value->id;
            $idbr = $idlm + 1;
            $num = sprintf("%06s", $idbr);
        }

        $data = 'PO';
        $kode_pembelian_part = $data . $num;
        return $kode_pembelian_part;
    }

    public function tambah_supplier(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_supp' => 'required',
                'alamat' => 'required',
                // 'nama_person' => 'required',
                // 'jabatan' => 'required',
                // 'fax' => 'required',
                // 'telp' => 'required',
                // 'hp' => 'required',
                // 'email' => 'required',
                // 'npwp' => 'required',
                // 'nama_bank' => 'required',
                // 'atas_nama' => 'required',
                // 'norek' => 'required',
            ],
            [
                'nama_supp.required' => 'Masukkan nama supplier',
                'alamat.required' => 'Masukkan Alamat',
                // 'nama_person.required' => 'Masukkan nama',
                // 'jabatan.required' => 'Masukkan jabatan',
                // 'telp.required' => 'Masukkan no telepon',
                // 'fax.required' => 'Masukkan no fax',
                // 'hp.required' => 'Masukkan no hp',
                // 'email.required' => 'Masukkan email',
                // 'npwp.required' => 'Masukkan no npwp',
                // 'nama_bank.required' => 'Masukkan nama bank',
                // 'atas_nama.required' => 'Masukkan atas nama',
                // 'norek.required' => 'Masukkan no rekening',
            ]
        );

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return back()->withInput()->with('error_pelanggans', $errors);
        }


        $kode_supp = $this->kode_supp();

        Supplier::create(array_merge(
            $request->all(),
            [
                'kode_supplier' => $this->kode_supp(),
                'qrcode_supplier' => 'https://javaline.id/supplier/' . $kode_supp,
                'tanggal_awal' => Carbon::now('Asia/Jakarta'),
                // 'qrcode_supplier' => 'http://192.168.1.46/javaline/supplier/' . $kode
            ]
        ));

        return back()->with('success', 'Berhasil menambahkan supplier');
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


    public function show($id)
    {

        $pembelian = Popembelian::find($id);
        $barangs = Detailpopembelian::where('popembelian_id', $pembelian->id)->get();


        $pembelians = Popembelian::where('id', $id)->first();

        return view('admin.popembelian.show', compact('barangs', 'pembelians'));
    }

    public function cetakpdf($id)
    {
        $pembelians = Popembelian::find($id);
        $parts = Detailpopembelian::where('popembelian_id', $pembelians->id)->get();
        $supplier = Supplier::where('id', $pembelians->supplier_id)->first();

        // Ubah tanggal pembelian menjadi objek Carbon dan format
        $tanggalPembelian = Carbon::parse($pembelians->tanggal)->format('Y-m-d');
        $namaSupplier = str_replace(' ', '_', $supplier->nama_supp);
        $namaFile = "{$namaSupplier}-{$tanggalPembelian}.pdf";

        // Load the view and set the paper size to portrait letter
        $pdf = PDF::loadView('admin.popembelian.cetak_pdf', compact('parts', 'pembelians'));
        $pdf->setPaper('letter', 'portrait');

        return $pdf->stream($namaFile);
    }
}