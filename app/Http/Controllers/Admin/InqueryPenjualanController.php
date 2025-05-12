<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Detailpenjualan;
use App\Models\Penjualan;
use App\Models\Satuan;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\Validator;

class InqueryPenjualanController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->status;
        $tanggal_awal = $request->tanggal_awal;
        $tanggal_akhir = $request->tanggal_akhir;

        $inquery = Penjualan::query();

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

        return view('admin/inquery_penjualan.index', compact('inquery'));
    }

    public function show($id)
    {

        $penjualan = Penjualan::where('id', $id)->first();
        $penjualan = Penjualan::find($id);

        $details = Detailpenjualan::where('penjualan_id', $penjualan->id)->get();

        return view('admin.inquery_penjualan.show', compact('details', 'penjualan'));
    }

    public function edit($id)
    {
        $inquery = Penjualan::where('id', $id)->first();
        $pelanggans = Pelanggan::all();
        $barangs = Barang::all();
        $satuans = Satuan::all();

        $details = Detailpenjualan::where('penjualan_id', $id)->get();

        return view('admin.inquery_penjualan.update', compact('satuans', 'inquery', 'details', 'pelanggans', 'barangs'));
    }

    public function update(Request $request, $id)
    {
        $validasi_pelanggan = Validator::make(
            $request->all(),
            [
                'kategori' => 'required',
                'pelanggan_id' => 'required',
            ],
            [
                'kategori.required' => 'Pilih kategori',
                'pelanggan_id.required' => 'Pilih nama supplier',
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
                    'qrcode_barang.' . $i => 'required',
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
                $qrcode_barang = is_null($request->qrcode_barang[$i]) ? '' : $request->qrcode_barang[$i];
                $kode_barang = is_null($request->kode_barang[$i]) ? '' : $request->kode_barang[$i];
                $nama_barang = is_null($request->nama_barang[$i]) ? '' : $request->nama_barang[$i];
                $harga = is_null($request->harga[$i]) ? '' : $request->harga[$i];
                $jumlah = is_null($request->jumlah[$i]) ? '' : $request->jumlah[$i];
                $diskon = is_null($request->diskon[$i]) ? '' : $request->diskon[$i];
                $satuan_id = is_null($request->satuan_id[$i]) ? '' : $request->satuan_id[$i];
                $total = is_null($request->total[$i]) ? '' : $request->total[$i];

                $data_pembelians->push([
                    'detail_id' => $request->detail_ids[$i] ?? null,
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
        } else {
        }

        if ($error_pelanggans || $error_pesanans) {
            return back()
                ->withInput()
                ->with('error_pelanggans', $error_pelanggans)
                ->with('error_pesanans', $error_pesanans)
                ->with('data_pembelians', $data_pembelians);
        }

        $transaksi = Penjualan::findOrFail($id);

        $transaksi->update([
            'pelanggan_id' => $request->pelanggan_id,
            'status' => 'posting',
        ]);

        $transaksi_id = $transaksi->id;


        $detailIds = $request->input('detail_ids');

        foreach ($data_pembelians as $data_pesanan) {
            $detailId = $data_pesanan['detail_id'];

            if ($detailId) {
                // Mendapatkan data Detail_pembelianpart yang akan diupdate
                $detailToUpdate = Detailpenjualan::find($detailId);

                if ($detailToUpdate) {
                    // Update Detail_pembelianpart
                    $detailToUpdate->update([
                        'penjualan_id' => $transaksi->id,
                        'barang_id' => $data_pesanan['barang_id'],
                        'qrcode_barang' => $data_pesanan['qrcode_barang'],
                        'kode_barang' => $data_pesanan['kode_barang'],
                        'nama_barang' => $data_pesanan['nama_barang'],
                        'jumlah' => $data_pesanan['jumlah'],
                        'diskon' => str_replace('.', '', $data_pesanan['diskon'] ?? '0'),
                        'satuan_id' => $data_pesanan['satuan_id'],
                        'harga' => str_replace('.', '', $data_pesanan['harga']),
                        'total' => str_replace('.', '', $data_pesanan['total']),
                    ]);
                }
            } else {
                Detailpenjualan::create([
                    'penjualan_id' => $transaksi->id,
                    'barang_id' => $data_pesanan['barang_id'],
                    'qrcode_barang' => $data_pesanan['qrcode_barang'],
                    'kode_barang' => $data_pesanan['kode_barang'],
                    'nama_barang' => $data_pesanan['nama_barang'],
                    'jumlah' => $data_pesanan['jumlah'],
                    'diskon' => str_replace('.', '', $data_pesanan['diskon'] ?? '0'),
                    'satuan_id' => $data_pesanan['satuan_id'],
                    'harga' => str_replace('.', '', $data_pesanan['harga']),
                    'total' => str_replace('.', '', $data_pesanan['total']),
                ]);
            }
        }


        $penjualan = Penjualan::find($transaksi_id);

        $details = Detailpenjualan::where('penjualan_id', $penjualan->id)->get();

        return view('admin.inquery_penjualan.show', compact('details', 'penjualan'));
    }


    public function unpost($id)
    {
        $penjualan = Penjualan::findOrFail($id);
        $detailpenjualan = Detailpenjualan::where('penjualan_id', $id)->get();

        foreach ($detailpenjualan as $detail) {
            $barang = Barang::find($detail['barang_id']);

            if ($barang) {
                $barang->jumlah += $detail->jumlah;
                $barang->save();
            }
        }
        $penjualan->update(['status' => 'unpost']);

        return back()->with('success', 'Penjualan berhasil di-unpost.');
    }

    public function posting($id)
    {
        $penjualan = Penjualan::findOrFail($id);
        $detailpenjualan = Detailpenjualan::where('penjualan_id', $id)->get();

        foreach ($detailpenjualan as $detail) {
            $barang = Barang::find($detail['barang_id']);

            if ($barang) {
                $barang->jumlah -= $detail->jumlah;
                $barang->save();
            }
        }
        $penjualan->update(['status' => 'posting']);

        return back()->with('success', 'Penjualan berhasil di-posting.');
    }

    public function deletedetailpenjualan($id)
    {
        $item = Detailpenjualan::find($id);

        if ($item) {
            $item->delete();
            return response()->json(['message' => 'Data deleted successfully']);
        } else {
            return response()->json(['message' => 'Detail Faktur not found'], 404);
        }
    }

    public function hapuspenjualan($id)
    {
        $penjualan = Penjualan::find($id);
        $penjualan->detailpenjualan()->delete();
        $penjualan->delete();


        return redirect('admin/inquery-penjualan')->with('success', 'Berhasil menghapus penjualan');
    }
}
