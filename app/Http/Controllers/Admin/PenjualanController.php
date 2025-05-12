<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Detailpenjualan;
use App\Models\Penjualan;
use App\Models\Satuan;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class PenjualanController extends Controller
{

    public function index()
    {
        $today = Carbon::today();

        $inquery = Penjualan::whereDate('created_at', $today)
            ->orWhere(function ($query) use ($today) {
                $query->where('status', 'unpost')
                    ->whereDate('created_at', '<', $today);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.penjualan.index', compact('inquery'));
    }

    public function create()
    {

        $pelanggans = Pelanggan::with('golongan')->get();
        $barangs = Barang::with('harga')->get();
        $satuans = Satuan::all();
        return view('admin.penjualan.create', compact('satuans', 'pelanggans', 'barangs'));
    }

    public function store(Request $request)
    {
        // Validasi pelanggan
        $validasi_pelanggan = Validator::make(
            $request->all(),
            [
                'kategori' => 'required',
                'pelanggan_id' => 'required',
            ],
            [
                'kategori.required' => 'Pilih Kategori',
                'pelanggan_id.required' => 'Pilih nama pelanggan',
            ]
        );

        $error_pelanggans = array();
        $error_pesanans = array();
        $data_pembelians = collect();

        // Cek jika validasi pelanggan gagal
        if ($validasi_pelanggan->fails()) {
            array_push($error_pelanggans, $validasi_pelanggan->errors()->all()[0]);
        }

        // Pastikan ada data barang minimal satu
        if (!$request->has('barang_id') || count($request->barang_id) == 0) {
            array_push($error_pesanans, 'Minimal 1 barang harus ditambahkan!');
        } else {
            // Validasi untuk setiap barang
            for ($i = 0; $i < count($request->barang_id); $i++) {
                $validasi_produk = Validator::make($request->all(), [
                    'barang_id.' . $i => 'required',
                    'qrcode_barang.' . $i => 'required',
                    'kode_barang.' . $i => 'required',
                    'nama_barang.' . $i => 'required',
                    'harga.' . $i => 'required',
                    'jumlah.' . $i => 'required',
                    'diskon.' . $i => 'required',
                    'satuan_id.' . $i => 'required',
                    'total.' . $i => 'required',
                ]);

                // Jika validasi gagal untuk produk, tampilkan pesan error
                if ($validasi_produk->fails()) {
                    array_push($error_pesanans, "Barang nomor " . ($i + 1) . " belum dilengkapi!");
                }

                // Push data barang ke koleksi jika valid
                $barang_id = $request->barang_id[$i] ?? '';
                $qrcode_barang = $request->qrcode_barang[$i] ?? '';
                $kode_barang = $request->kode_barang[$i] ?? '';
                $nama_barang = $request->nama_barang[$i] ?? '';
                $harga = $request->harga[$i] ?? '';
                $jumlah = $request->jumlah[$i] ?? '';
                $diskon = $request->diskon[$i] ?? '';
                $satuan_id = $request->satuan_id[$i] ?? '';
                $total = $request->total[$i] ?? '';

                $data_pembelians->push([
                    'barang_id' => $barang_id,
                    'qrcode_barang' => $qrcode_barang,
                    'kode_barang' => $kode_barang,
                    'nama_barang' => $nama_barang,
                    'harga' => $harga,
                    'jumlah' => $jumlah,
                    'diskon' => $diskon,
                    'satuan_id' => $satuan_id,
                    'total' => $total
                ]);
            }
        }

        // Jika ada error, kembali dengan pesan error dan data
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
        $transaksi = Penjualan::create([
            'kode_penjualan' => $this->kode(),
            'kategori' => $request->kategori,
            'pelanggan_id' => $request->pelanggan_id,
            'qrcode_penjualan' => 'https://javateknik.id/penjualan/' . $kode,
            'tanggal' => $format_tanggal,
            'tanggal_awal' => $tanggal,
            'pelanggan_id' => $request->pelanggan_id,
            'total_harga' => str_replace(',', '.', str_replace('.', '', $request->total_harga)),
            'ppn' => str_replace(',', '.', str_replace('.', '', $request->ppn)),
            'grand_total' => str_replace(',', '.', str_replace('.', '', $request->grand_total)),
            'status' => 'posting',
            'status_notif' => false,
        ]);

        $transaksi_id = $transaksi->id;

        if ($transaksi) {
            foreach ($data_pembelians as $data_pembelian) {
                $barang = Barang::find($data_pembelian['barang_id']);
                if ($barang) {
                    $jumlah_barang = $barang->jumlah - $data_pembelian['jumlah'];
                    $barang->update(['jumlah' => $jumlah_barang]);
                    Detailpenjualan::create([
                        'penjualan_id' => $transaksi->id,
                        'barang_id' => $data_pembelian['barang_id'],
                        'qrcode_barang' => $data_pembelian['qrcode_barang'],
                        'kode_barang' => $data_pembelian['kode_barang'],
                        'nama_barang' => $data_pembelian['nama_barang'],
                        'jumlah' => $data_pembelian['jumlah'],
                        'diskon' => str_replace('.', '', $data_pesanan['diskon'] ?? '0'),
                        'satuan_id' => $data_pembelian['satuan_id'],
                        'harga' => str_replace('.', '', $data_pembelian['harga']),
                        'total' => str_replace('.', '', $data_pembelian['total']),
                    ]);
                }
            }
        }

        $penjualan = Penjualan::find($transaksi_id);

        $details = Detailpenjualan::where('penjualan_id', $penjualan->id)->get();

        return view('admin.penjualan.show', compact('details', 'penjualan'));
    }

    public function kode()
    {
        $pembelian_part = Penjualan::all();
        if ($pembelian_part->isEmpty()) {
            $num = "000001";
        } else {
            $id = Penjualan::getId();
            foreach ($id as $value);
            $idlm = $value->id;
            $idbr = $idlm + 1;
            $num = sprintf("%06s", $idbr);
        }

        $data = 'AJ';
        $kode_pembelian_part = $data . $num;
        return $kode_pembelian_part;
    }

    public function show($id)
    {

        $penjualan = Penjualan::find($id);
        $details = Detailpenjualan::where('penjualan_id', $penjualan->id)->get();

        return view('admin.penjualan.show', compact('penjualan', 'details'));
    }

    public function cetakpdf($id)
    {

        $penjualan = Penjualan::find($id);
        $details = Detailpenjualan::where('penjualan_id', $penjualan->id)->get();

        $pdf = PDF::loadView('admin.penjualan.cetak_pdf', compact('penjualan', 'details'));
        $pdf->setPaper('letter', 'portrait'); // Set the paper size to portrait letter

        return $pdf->stream('Faktur_Penjualan.pdf');
    }
}
