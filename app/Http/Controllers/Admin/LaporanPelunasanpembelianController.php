<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Detail_pelunasanpembelian;
use App\Models\Faktur_pelunasanpembelian;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanPelunasanpembelianController extends Controller

{
    public function index(Request $request)
    {

        $status = $request->status;
        $created_at = $request->created_at;
        $tanggal_akhir = $request->tanggal_akhir;

        $inquery = Detail_pelunasanpembelian::orderBy('id', 'DESC');

        if ($status == "posting") {
            $inquery->where('status', $status);
        } else {
            $inquery->where('status', 'posting');
        }

        if ($created_at && $tanggal_akhir) {
            $inquery->whereDate('created_at', '>=', $created_at)
                ->whereDate('created_at', '<=', $tanggal_akhir);
        }

        // $inquery = $inquery->get();

        // kondisi sebelum melakukan pencarian data masih kosong
        $hasSearch = $status || ($created_at && $tanggal_akhir);
        $inquery = $hasSearch ? $inquery->get() : collect();

        return view('admin.laporan_pelunasanpembelian.index', compact('inquery'));

    }

    public function print_pelunasanpembelian(Request $request)
    {

        $status = $request->status;
        $created_at = $request->created_at;
        $tanggal_akhir = $request->tanggal_akhir;

        $query = Detail_pelunasanpembelian::orderBy('id', 'DESC');

        if ($status == "posting") {
            $query->where('status', $status);
        } else {
            $query->where('status', 'posting');
        }

        if ($created_at && $tanggal_akhir) {
            $query->whereDate('created_at', '>=', $created_at)
                ->whereDate('created_at', '<=', $tanggal_akhir);
        }
        $inquery = $query->orderBy('id', 'DESC')->get();

        $pdf = Pdf::loadView('admin.laporan_pelunasanpembelian.print', compact('inquery'));
        return $pdf->stream('Laporan_Pelunasan.pdf');

    }


    public function indexglobalpembelian(Request $request)
    {

        $status = $request->status;
        $tanggal_awal = $request->tanggal_awal;
        $tanggal_akhir = $request->tanggal_akhir;

        $inquery = Faktur_pelunasanpembelian::orderBy('id', 'DESC');

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

        $inquery = $inquery->get();

        // kondisi sebelum melakukan pencarian data masih kosong
        $hasSearch = $status || ($tanggal_awal && $tanggal_akhir);
        $inquery = $hasSearch ? $inquery : collect();

        return view('admin.laporan_pelunasanpembelian.indexglobal', compact('inquery'));
    
    }



    public function print_pelunasanglobalpembelian(Request $request)
    {

        $status = $request->status;
        $tanggal_awal = $request->tanggal_awal;
        $tanggal_akhir = $request->tanggal_akhir;

        $query = Faktur_pelunasanpembelian::orderBy('id', 'DESC');

        if ($status == "posting") {
            $query->where('status', $status);
        } else {
            $query->where('status', 'unpost');
        }

        if ($tanggal_awal && $tanggal_akhir) {
            $query->whereDate('tanggal_awal', '>=', $tanggal_awal)
                ->whereDate('tanggal_awal', '<=', $tanggal_akhir);
        }
        $inquery = $query->orderBy('id', 'DESC')->get();

        $pdf = PDF::loadView('admin.laporan_pelunasanpembelian.printglobal', compact('inquery'));
        return $pdf->stream('Laporan_Pelunasan.pdf');

    }
}