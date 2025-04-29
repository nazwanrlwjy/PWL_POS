<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LevelController extends Controller
{
    // Menampilkan halaman daftar level
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Level',
            'list'  => ['Home', 'Level']
        ];

        $page = (object) [
            'title' => 'Daftar level yang tersedia dalam sistem'
        ];

        $activeMenu = 'level'; // set menu yang sedang aktif

        return view('level.index', [
            'breadcrumb' => $breadcrumb, 
            'page' => $page, 
            'activeMenu' => $activeMenu
        ]);
    }

    // Ambil data level dalam bentuk JSON untuk DataTables
    public function list(Request $request)
    {
        $query = LevelModel::select('level_id', 'level_kode', 'level_name');

        return DataTables::of($query)
            ->addIndexColumn() // Menambahkan nomor urut
            ->addColumn('aksi', function ($row) {
                return '<a href="'.url('level/'.$row->level_id).'" class="btn btn-sm btn-info">Detail</a>
                        <a href="'.url('level/'.$row->level_id.'/edit').'" class="btn btn-sm btn-warning">Edit</a>
                        <form action="'.url('level/'.$row->level_id).'" method="POST" style="display:inline;">
                            '.csrf_field().method_field("DELETE").'
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Yakin ingin menghapus?\')">Hapus</button>
                        </form>';
            })
            ->rawColumns(['aksi']) // Supaya HTML dalam kolom aksi bisa dirender
            ->make(true);
    }

    // Menampilkan halaman form tambah level
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Level',
            'list'  => ['Home', 'Level', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah Level Baru'
        ];

        $activeMenu = 'level';

        return view('level.create', [
            'breadcrumb' => $breadcrumb,
            'page'       => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    // Menyimpan data level baru
    public function store(Request $request)
    {
        $request->validate([
            'level_kode' => 'required|string|max:10|unique:m_level,level_kode',
            'level_name' => 'required|string|max:100',
        ]);

        LevelModel::create([
            'level_kode' => $request->level_kode,
            'level_name' => $request->level_name
        ]);

        return redirect('/level')->with('success', 'Data level berhasil disimpan');
    }

    // Menampilkan detail level
    public function show(string $id)
    {
        $level = LevelModel::find($id);

        if (!$level) {
            return redirect('/level')->with('error', 'Data level tidak ditemukan');
        }

        $breadcrumb = (object) [
            'title' => 'Detail Level',
            'list'  => ['Home', 'Level', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail Level'
        ];

        $activeMenu = 'level';

        return view('level.show', [
            'breadcrumb' => $breadcrumb,
            'page'       => $page,
            'level'      => $level,
            'activeMenu' => $activeMenu
        ]);
    }

    // Menampilkan halaman form edit level
    public function edit($id)
     {
         $level = LevelModel::findOrFail($id);
 
         $breadcrumb = (object) [
             'title' => 'Edit Level',
             'list' => ['Home', 'Level', 'Edit']
         ];
 
         $page = (object) [
             'title' => 'Edit Data Level'
         ];
 
         $activeMenu = 'level';
 
         return view('level.edit', compact('breadcrumb', 'page', 'level', 'activeMenu'));
     }

    // Menyimpan perubahan data level
    public function update(Request $request, $id)
     {

         $request->validate([
             'level_kode' => 'required|string|unique:m_level,level_kode,' . $id . ',level_id',
             'level_name' => 'required|string'
         ]);
 
         $level = LevelModel::findOrFail($id);
         $level->update([
             'level_kode' => $request->level_kode,
             'level_name' => $request->level_name
         ]);
 
         return redirect('/level')->with('success', 'Data Level berhasil diperbarui');
     }
 

    // Menghapus data level
    public function destroy(string $id)
    {
        $level = LevelModel::find($id);
        if (!$level) {
            return redirect('/level')->with('error', 'Data level tidak ditemukan');
        }

        try {
            LevelModel::destroy($id);
            return redirect('/level')->with('success', 'Data level berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/level')->with('error', 'Data level gagal dihapus karena masih terkait dengan data lain.');
        }
    }
}