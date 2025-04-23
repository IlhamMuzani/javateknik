<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Dompdf\Dompdf;
use App\Models\Menufitur;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class MenufiturController extends Controller
{
    public function index()
    {

        $menufiturs = Menufitur::all();
        return view('admin/menufitur.index', compact('menufiturs'));
    }

    public function create()
    {

        return view('admin/menufitur.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'kategori' => 'required',
                'nama' => 'required',
            ],
            [
                'kategori.required' => 'Masukkan kategori',
                'nama.required' => 'Masukkan nama',
            ]
        );

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        Menufitur::create(array_merge(
            $request->all(),
            [
                'kategori' => $request->kategori,
                'nama' => $request->nama,
                'route' => $request->route,
            ]
        ));

        return redirect('admin/menu-fitur')->with('success', 'Berhasil menambahkan menufitur');
    }

    public function edit($id)
    {
        $menufitur = Menufitur::where('id', $id)->first();
        return view('admin/menufitur.update', compact('menufitur'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'kategori' => 'required',
                'nama' => 'required',
            ],
            [
                'kategori.required' => 'Masukkan kategori',
                'nama.required' => 'Masukkan nama',
            ]
        );

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }
        $menufitur = Menufitur::find($id);
        $menufitur->kategori = $request->kategori;
        $menufitur->nama = $request->nama;
        $menufitur->route = $request->route;
        $menufitur->save();
        return redirect('admin/menu-fitur')->with('success', 'Berhasil memperbarui menufitur');
    }

    public function destroy($id)
    {
        $menufitur = Menufitur::find($id);
        $menufitur->delete();
        return redirect('admin/menu-fitur')->with('success', 'Berhasil menghapus menufitur');
    }
}
