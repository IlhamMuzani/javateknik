<?php

namespace App\Http\Controllers\admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Detail_pelunasanpenjualan;
use App\Models\Penjualan;
use App\Models\Pelanggan;
use App\Models\Faktur_pelunasanpenjualan;
use App\Models\Nota_return;
use App\Models\Return_ekspedisi;
use App\Models\Satuan;
use Illuminate\Support\Facades\Validator;

class InqueryFakturpelunasanpenjualanController extends Controller
{
    public function index(Request $request)
    {
        Faktur_pelunasanpenjualan::where([
            ['status', 'posting']
        ])->update([
            'status_notif' => true
        ]);

        $status = $request->status;
        $tanggal_awal = $request->tanggal_awal;
        $tanggal_akhir = $request->tanggal_akhir;

        $inquery = Faktur_pelunasanpenjualan::query();

        if ($status) {
            $inquery->where('status', $status);
        }

        if ($tanggal_awal && $tanggal_akhir) {
            $inquery->whereBetween('tanggal_awal', [$tanggal_awal, $tanggal_akhir]);
        } elseif ($tanggal_awal) {
            $inquery->where('tanggal_awal', '>=', $tanggal_awal);
        } elseif ($tanggal_akhir) {
            $inquery->where('tanggal_awal', '<=', $tanggal_akhir);
        } else {
            // Jika tidak ada filter tanggal hari ini
            $inquery->whereDate('tanggal_awal', Carbon::today());
        }

        $inquery->orderBy('id', 'DESC');
        $inquery = $inquery->get();

        return view('admin.inquery_fakturpelunasanpenjualan.index', compact('inquery'));
    }



    public function edit($id)
    {
        $inquery = Faktur_pelunasanpenjualan::where('id', $id)->first();
        $details  = Detail_pelunasanpenjualan::where('faktur_pelunasanpenjualan_id', $id)->get();
        $pelanggans = Pelanggan::all();
        $fakturs = Penjualan::where(['status_pelunasan' => null, 'status' => 'posting'])->get();
        return view('admin.inquery_fakturpelunasanpenjualan.update', compact('details', 'inquery', 'pelanggans', 'fakturs'));
    }

    public function update(Request $request, $id)
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
                    'detail_id' => $request->detail_ids[$i] ?? null,
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

        $tanggal1 = Carbon::now('Asia/Jakarta');
        $format_tanggal = $tanggal1->format('d F Y');

        $tanggal = Carbon::now()->format('Y-m-d');
        $cetakpdf = Faktur_pelunasanpenjualan::findOrFail($id);

        // Update the main transaction
        $cetakpdf->update([
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
            'status' => 'posting',
            'tanggal_transfer' => $request->tanggal_transfer,
            // 'nominal' => str_replace('.', '', $request->nominal),
            'nominal' =>  $request->nominal ? str_replace(',', '.', str_replace('.', '', $request->nominal)) : 0,

        ]);

        $transaksi_id = $cetakpdf->id;
        $detailIds = $request->input('detail_ids');

        foreach ($data_pembelians as $data_pesanan) {
            $detailId = $data_pesanan['detail_id'];

            if ($detailId) {
                $detailPelunasan = Detail_pelunasanpenjualan::where('id', $detailId)->update([
                    'faktur_pelunasanpenjualan_id' => $cetakpdf->id,
                    'penjualan_id' => $data_pesanan['penjualan_id'],
                    'kode_penjualan' => $data_pesanan['kode_penjualan'],
                    'tanggal_penjualan' => $data_pesanan['tanggal_penjualan'],
                    'status' => 'posting',
                    'total' => str_replace(',', '.', str_replace('.', '', $data_pesanan['total'])),
                ]);

                // Update Penjualan
                Penjualan::where('id', $data_pesanan['penjualan_id'])->update(['status' => 'selesai', 'status_pelunasan' => 'aktif']);
            } else {
                $existingDetail = Detail_pelunasanpenjualan::where([
                    'faktur_pelunasanpenjualan_id' => $cetakpdf->id,
                    'penjualan_id' => $data_pesanan['penjualan_id'],
                    'kode_penjualan' => $data_pesanan['kode_penjualan'],
                    'tanggal_penjualan' => $data_pesanan['tanggal_penjualan'],
                    'total' => str_replace(',', '.', str_replace('.', '', $data_pesanan['total'])),
                ])->first();

                if (!$existingDetail) {
                    $detailPelunasan = Detail_pelunasanpenjualan::create([
                        'faktur_pelunasanpenjualan_id' => $cetakpdf->id,
                        'status' => 'posting',
                        'penjualan_id' => $data_pesanan['penjualan_id'],
                        'kode_penjualan' => $data_pesanan['kode_penjualan'],
                        'tanggal_penjualan' => $data_pesanan['tanggal_penjualan'],
                        'total' => str_replace(',', '.', str_replace('.', '', $data_pesanan['total'])),
                    ]);

                    // Update Penjualan
                    Penjualan::where('id', $detailPelunasan->penjualan_id)->update(['status' => 'selesai', 'status_pelunasan' => 'aktif']);
                }
            }
        }

        $details = Detail_pelunasanpenjualan::where('faktur_pelunasanpenjualan_id', $cetakpdf->id)->get();

        return view('admin.inquery_fakturpelunasanpenjualan.show', compact('cetakpdf', 'details'));
    }

    public function show($id)
    {
        $cetakpdf = Faktur_pelunasanpenjualan::where('id', $id)->first();
        $details = Detail_pelunasanpenjualan::where('faktur_pelunasanpenjualan_id', $id)->get();

        return view('admin.inquery_fakturpelunasanpenjualan.show', compact('cetakpdf', 'details'));
    }

    public function unpost($id)
    {
        // Menggunakan find untuk mendapatkan Faktur_pelunasanpenjualan berdasarkan ID
        $item = Faktur_pelunasanpenjualan::find($id);

        // Memeriksa apakah Faktur_pelunasanpenjualan ditemukan
        if (!$item) {
            return back()->with('error', 'Faktur penjualan tidak ditemukan');
        }

        // Mendapatkan detail pelunasan terkait
        $detailpelunasan = Detail_pelunasanpenjualan::where('faktur_pelunasanpenjualan_id', $id)->get();

        // Melakukan loop pada setiap Detail_pelunasanpenjualan dan memperbarui rekaman Penjualan terkait
        foreach ($detailpelunasan as $detail) {
            if ($detail->penjualan_id) {
                // Menggunakan find untuk mendapatkan Penjualan berdasarkan ID
                $fakturEkspedisi = Penjualan::find($detail->penjualan_id);

                // Memeriksa apakah Penjualan ditemukan
                if ($fakturEkspedisi) {
                    // Memperbarui status_pelunasan pada Penjualan menjadi 'aktif'
                    $fakturEkspedisi->update(['status' => 'posting', 'status_pelunasan' => null]);
                }
            }
        }

        try {
            // Memperbarui status pada Faktur_pelunasanpenjualan menjadi 'unpost'
            $item->update(['status' => 'unpost']);

            // Melakukan loop pada setiap Detail_pelunasanpenjualan dan memperbarui status menjadi 'unpost'
            foreach ($detailpelunasan as $detail) {
                $detail->update(['status' => 'unpost']);
            }

            return back()->with('success', 'Berhasil unposting faktur penjualan');
        } catch (\Exception $e) {
            // Menangani kesalahan pembaruan basis data
            return back()->with('error', 'Gagal unposting faktur penjualan: ' . $e->getMessage());
        }
    }


    public function posting($id)
    {
        // Menggunakan find untuk mendapatkan Faktur_pelunasanpenjualan berdasarkan ID
        $item = Faktur_pelunasanpenjualan::find($id);

        // Memeriksa apakah Faktur_pelunasanpenjualan ditemukan
        if (!$item) {
            return back()->with('error', 'Faktur penjualan tidak ditemukan');
        }

        // Mendapatkan detail pelunasan terkait
        $detailpelunasan = Detail_pelunasanpenjualan::where('faktur_pelunasanpenjualan_id', $id)->get();

        try {
            // Melakukan loop pada setiap Detail_pelunasanpenjualan dan memperbarui status menjadi 'posting'
            foreach ($detailpelunasan as $detail) {
                $detail->update(['status' => 'posting']);
            }

            // Memperbarui status pada Faktur_pelunasanpenjualan menjadi 'posting'
            $item->update(['status' => 'posting']);

            return back()->with('success', 'Berhasil posting penjualan');
        } catch (\Exception $e) {
            // Menangani kesalahan pembaruan basis data
            return back()->with('error', 'Gagal posting penjualan: ' . $e->getMessage());
        }
    }


    public function hapuspelunasanpenjualan($id)
    {
        $item = Faktur_pelunasanpenjualan::where('id', $id)->first();

        if ($item) {
            $detailpelunasan = Detail_pelunasanpenjualan::where('faktur_pelunasanpenjualan_id', $id)->get();

            // Loop through each Detail_pelunasanpenjualan and update associated Penjualan records
            // foreach ($detailpelunasan as $detail) {
            //     if ($detail->penjualan_id) {
            //         Penjualan::where('id', $detail->penjualan_id)->update(['status_pelunasan' => null, 'status' => 'posting']);
            //     }
            // }
            // Delete related Detail_pelunasanpenjualan instances
            Detail_pelunasanpenjualan::where('faktur_pelunasanpenjualan_id', $id)->delete();

            // Delete the main Faktur_pelunasanpenjualan instance
            $item->delete();

            return back()->with('success', 'Berhasil menghapus penjualan');
        } else {
            // Handle the case where the Faktur_pelunasanpenjualan with the given ID is not found
            return back()->with('error', 'penjualan tidak ditemukan');
        }
    }
}