<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Detail_pelunasanpenjualan;
use App\Models\Faktur_pelunasanpenjualan;
use App\Models\Penjualan;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\Validator;

class PelunasanpenjualanController extends Controller
{

    public function index()
    {
        $today = Carbon::today();

        $inquery = Faktur_pelunasanpenjualan::whereDate('created_at', $today)
            ->orWhere(function ($query) use ($today) {
                $query->where('status', 'unpost')
                    ->whereDate('created_at', '<', $today);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.faktur_pelunasanpenjualan.index', compact('inquery'));
    }

    public function create()
    {
        $pelanggans = Pelanggan::all();
        $fakturs = Penjualan::where(['status_pelunasan' => null, 'status' => 'posting'])->get();

        return view('admin.faktur_pelunasanpenjualan.create', compact('pelanggans', 'fakturs'));
    }

    public function store(Request $request)
    {
        $validasi_pelanggan = Validator::make(
            $request->all(),
            [
                'pelanggan_id' => 'required',
                'penjualan_id' => 'required',
            ],
            [
                'pelanggan_id.required' => 'Pilih Pelanggan',
                'penjualan_id.required' => 'Pilih Faktur Penjualan',
            ]
        );

        $error_pelanggans = array();

        if ($validasi_pelanggan->fails()) {
            array_push($error_pelanggans, $validasi_pelanggan->errors()->all()[0]);
        }

        $error_pesanans = array();
        $data_pembelians = collect();

        if ($request->has('penjualan_id')) {
            for ($i = 0; $i < count($request->penjualan_id); $i++) {
                $validasi_produk = Validator::make($request->all(), [
                    'penjualan_id.' . $i => 'required',
                    'kode_penjualan.' . $i => 'required',
                    'tanggal_penjualan.' . $i => 'required',
                    'total.' . $i => 'required',
                ]);

                if ($validasi_produk->fails()) {
                    array_push($error_pesanans, "Faktur nomor " . ($i + 1) . " belum dilengkapi!"); // Corrected the syntax for concatenation and indexing
                }
                $penjualan_id = is_null($request->penjualan_id[$i]) ? '' : $request->penjualan_id[$i];
                $kode_penjualan = is_null($request->kode_penjualan[$i]) ? '' : $request->kode_penjualan[$i];
                $tanggal_penjualan = is_null($request->tanggal_penjualan[$i]) ? '' : $request->tanggal_penjualan[$i];
                $total = is_null($request->total[$i]) ? '' : $request->total[$i];

                $data_pembelians->push([
                    'penjualan_id' => $penjualan_id,
                    'kode_penjualan' => $kode_penjualan,
                    'tanggal_penjualan' => $tanggal_penjualan,
                    'total' => $total
                ]);
            }
        }

        if ($error_pelanggans || $error_pesanans) {
            return back()
                ->withInput()
                ->with('error_pelanggans', $error_pelanggans)
                ->with('error_pesanans', $error_pesanans)
                ->with('data_pembelians', $data_pembelians);
        }



        $kode = $this->kode();
        // format tanggal indo
        $tanggal1 = Carbon::now('Asia/Jakarta');
        $format_tanggal = $tanggal1->format('d F Y');

        $selisih = (int)str_replace(['Rp', '.', ' '], '', $request->selisih);
        $totalpembayaran = (int)str_replace(['Rp', '.', ' '], '', $request->totalpembayaran);
        $tanggal = Carbon::now()->format('Y-m-d');
        $cetakpdf = Faktur_pelunasanpenjualan::create([
            'user_id' => auth()->user()->id,
            'kode_pelunasan' => $this->kode(),
            'pelanggan_id' => $request->pelanggan_id,
            'kode_pelanggan' => $request->kode_pelanggan,
            'nama_pelanggan' => $request->nama_pelanggan,
            'alamat_pelanggan' => $request->alamat_pelanggan,
            'telp_pelanggan' => $request->telp_pelanggan,
            'keterangan' => $request->keterangan,
            'totalpenjualan' => str_replace(',', '.', str_replace('.', '', $request->totalpenjualan)),
            'dp' => str_replace(',', '.', str_replace('.', '', $request->dp)),
            'potonganselisih' => str_replace(',', '.', str_replace('.', '', $request->potonganselisih)),
            'totalpembayaran' => str_replace(',', '.', str_replace('.', '', $request->totalpembayaran)),
            'selisih' => str_replace(',', '.', str_replace('.', '', $request->selisih)),
            'potongan' => $request->potongan ? str_replace(',', '.', str_replace('.', '', $request->potongan)) : 0,
            'tambahan_pembayaran' => $request->tambahan_pembayaran ? str_replace(',', '.', str_replace('.', '', $request->tambahan_pembayaran)) : 0,
            'kategori' => $request->kategori,
            'nomor' => $request->nomor,
            'tanggal_transfer' => $request->tanggal_transfer,
            'nominal' =>  $request->nominal ? str_replace(',', '.', str_replace('.', '', $request->nominal)) : 0,
            'tanggal' => $format_tanggal,
            'tanggal_awal' => $tanggal,
            'qrcode_pelunasanpenjualan' => 'https://javaline.id/faktur_pelunasanpenjualan/' . $kode,
            'status' => 'posting',
            'status_notif' => false,
        ]);

        $transaksi_id = $cetakpdf->id;

        foreach ($data_pembelians as $data_pesanan) {
            $detailPelunasan = Detail_pelunasanpenjualan::create([
                'faktur_pelunasanpenjualan_id' => $cetakpdf->id,
                'status' => 'posting',
                'penjualan_id' => $data_pesanan['penjualan_id'],
                'kode_penjualan' => $data_pesanan['kode_penjualan'],
                'tanggal_penjualan' => $data_pesanan['tanggal_penjualan'],
                'total' => str_replace(',', '.', str_replace('.', '', $data_pesanan['total'])),
            ]);

            // Assuming the status_pelunasan update is correct
            Penjualan::where('id', $detailPelunasan->penjualan_id)->update(['status' => 'selesai', 'status_pelunasan' => 'aktif']);
        }


        $details = Detail_pelunasanpenjualan::where('faktur_pelunasanpenjualan_id', $cetakpdf->id)->get();

        return view('admin.faktur_pelunasanpenjualan.show', compact('cetakpdf', 'details'));
    }


    public function kode()
    {
        $lastBarang = Faktur_pelunasanpenjualan::latest()->first();
        if (!$lastBarang) {
            $num = 1;
        } else {
            $lastCode = $lastBarang->kode_pelunasan;
            $num = (int) substr($lastCode, strlen('LJ')) + 1;
        }
        $formattedNum = sprintf("%06s", $num);
        $prefix = 'LJ';
        $newCode = $prefix . $formattedNum;
        return $newCode;
    }

    public function show($id)
    {
        $cetakpdf = Faktur_pelunasanpenjualan::where('id', $id)->first();
        $details = Detail_pelunasanpenjualan::where('faktur_pelunasanpenjualan_id', $id)->get();

        return view('admin.faktur_pelunasanpenjualan.show', compact('cetakpdf', 'details'));
    }

    public function cetakpdf($id)
    {
        $cetakpdf = Faktur_pelunasanpenjualan::where('id', $id)->first();
        $details = Detail_pelunasanpenjualan::where('faktur_pelunasanpenjualan_id', $cetakpdf->id)->get();

        $pdf = PDF::loadView('admin.faktur_pelunasanpenjualan.cetak_pdf', compact('cetakpdf', 'details'));
        $pdf->setPaper('letter', 'portrait'); // Set the paper size to portrait letter

        return $pdf->stream('Faktur_Pelunasan_Penjualan.pdf');
    }
}