<?php

namespace App\Http\Controllers;

use App\Models\KategoriModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;

class KategoriController extends Controller
{
    public function index(){
        $breadcrumb = (object)[
            'title' => 'Daftar kategori',
            'list' => ['Home', 'Kategori']
        ];

        $page = (object)[
            'title' => 'Daftar Kategori yang terdaftar dalam sistem'
        ];

        $activeMenu = 'kategori'; // set menu yang sedang aktif

        return view('kategori.index', ['breadcrumb' => $breadcrumb, 'page' => $page,'activeMenu' => $activeMenu]);
    }

    public function list(Request $request){
        $kategoris = KategoriModel::select('kategori_id', 'nama_kategori', 'deskripsi');
    
        return DataTables::of($kategoris)
            ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addColumn('aksi', function ($kategori) { // menambahkan kolom aksi
                // $btn = '<a href="'.url('/kategori/' . $kategori->kategori_id).'" class="btn btn-info btn-sm">Detail</a> ';
                // $btn .= '<a href="'.url('/kategori/' . $kategori->kategori_id . '/edit').'" class="btn btn-warning btn-sm">Edit</a> ';
                // $btn .= '<form class="d-inline-block" method="POST" action="'.url('/kategori/' . $kategori->kategori_id).'">';
                // $btn .= csrf_field() . method_field("DELETE");
                // $btn .= '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\')">Hapus</button>';
                // $btn .= '</form>';
                // return $btn;

                $btn  = '<button onclick="modalAction(\''.url('/kategori/' . $kategori->kategori_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/kategori/' . $kategori->kategori_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/kategori/' . $kategori->kategori_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
    
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah HTML
            ->make(true);
    }

    public function create(){
        $breadcrumb = (object)[
            'title' => 'Tambah Kategori',
            'list' => ['Home', 'Kategori', 'Tambah']
        ];

        $page = (object)[
            'title' => 'Tambah kategori baru'
        ];

        $activeMenu = 'kategori';

        return view('kategori.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function store(Request $request){
        $request->validate([
            'nama_kategori' => 'required|string|unique:m_kategori,nama_kategori',
            'deskripsi' => 'required|min:5',
        ]);

        KategoriModel::create([
            'nama_kategori' => $request->nama_kategori,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect('/kategori')->with('Success', 'Data berhasil disimpan');
    }
    public function show(string $id){
        $user = KategoriModel::find($id);

        $breadcrumb = (object)[
            'title' => 'Detail Kategori',
            'list' => ['Home', 'Kategori', 'Detail']
        ];

        $page = (object)[
            'title' => 'Detail kategori'
        ];

        $activeMenu = 'kategori';

        return view('kategori.show',['user' => $user, 'breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }
    public function edit(string $id){
        $user = KategoriModel::find($id);

        $breadcrumb = (object)[
            'title' => 'Edit Kategori',
            'list' => ['Home', 'kategori', 'Edit'],
        ];

        $page = (object)[
            'title' => 'Edit Kategori'
        ];

        $activeMenu = 'kategori';

        return view('kategori.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'activeMenu' => $activeMenu]);
    }
    public function update(Request $request, string $id){
        $request->validate([
            'nama_kategori' => 'required|string|unique:m_kategori,nama_kategori',
            'deskripsi' => 'required|min:5',
        ]);

        KategoriModel::find($id)->update([
            'nama_kategori' => $request->nama_kategori,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect('/kategori')->with('Success', 'Data berhasil disimpan');
    }
    public function destroy(string $id){
        $check = KategoriModel::find($id);

        if (!$check) { // cek data ada atau tidak
            return redirect('/kategori')->with('error','data tidak ditemukan');
        }

        try{
            KategoriModel::destroy($id);// hapus data levelS
            return redirect('/kategori')->with('success','Data kategori berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e){
            return redirect('/kategori')->with('error', 'Data user gagal dihapus karena terdapat tabel lain yang masih terkait dengan data ini');
        }
    }

    public function create_ajax(){
        return view('kategori.create_ajax');
    }

    public function store_ajax(Request $request){
        //cek req ajax
        if ($request->ajax()|| $request->wantsJson()) {
            $rules =[
                'nama_kategori' => 'required|string|unique:m_kategori,nama_kategori',
                'deskripsi' => 'required|string',
            ]; 
            
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false, //response status
                    'message' => 'Validasi Gagal', //pesan gagal
                    'msgField' => $validator->errors(), //pesan error
                ]);
            }
            KategoriModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'data user berhasil disimpan'
            ]);
        }
        redirect('/');
    }

    public function edit_ajax(string $id){
        $kategori = KategoriModel::find($id);
        return view('kategori.edit_ajax',['kategori' => $kategori]);
    }

    public function update_ajax(Request $request, $id){       
    // Cek apakah request berasal dari AJAX
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'nama_kategori' => 'required|string|unique:m_kategori,nama_kategori,'.$id.',kategori_id',
            'deskripsi' => 'required|string',
        ];

        // Validasi input
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false, // Respon JSON, true: berhasil, false: gagal
                'message'   => 'Validasi gagal.',
                'msgField'  => $validator->errors() // Menunjukkan field mana yang error
            ]);
        }

        // Cek apakah user dengan ID yang diberikan ada
        $check = KategoriModel::find($id);
        if ($check) {

            $check->update($request->all());

            return response()->json([
                'status'  => true,
                'message' => 'Data berhasil diupdate'
            ]);
        } else {
            return response()->json([
                'status'  => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    return redirect('/');
}

    public function confirm_ajax(string $id){
        $kategori = KategoriModel::find($id);
        return view('kategori.confirm_ajax', ['kategori' => $kategori]);
    }

    public function delete_ajax(Request $request, $id){
        if ($request->ajax() || $request->wantsJson()) {
            $user = KategoriModel::find($id);
            if ($user) {
                $user->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'data berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'data tidak ditemukan'
                ]);
            }
        }
    }

    public function import()
    {
        return view('kategori.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_kategori' => ['required', 'mimes:xlsx', 'max:1024']
            ];
    
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }
    
            $file = $request->file('file_kategori'); 
    
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);
    
            $insert = [];
            if (count($data) > 1) { 
                foreach ($data as $baris => $value) {
                    if ($baris > 1) { 
                        $insert[] = [
                            'nama_kategori' => $value['A'],
                            'deskripsi' => $value['B'],
                            'created_at' => now(),
                        ];
                    }
                }
    
                if (count($insert) > 0) {
                    KategoriModel::insertOrIgnore($insert);
                }
    
                return response()->json([
                    'status'  => true,
                    'message' => 'Data kategori berhasil diimport'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => 'Tidak ada data yang diimport'
                ]);
            }
        }
        return redirect('/');
    }
    // public function index(){
    //     // $data =[
    //     //     'nama_kategori' => 'SNK',
    //     //     'deskripsi' => 'Snake/Makanan Ringan',
    //     //     'created_at' => now()
    //     // ];

    //     // DB::table('m_kategori')->insert($data);
    //     // return 'Insert data baru berhasil';

    //     // $row = DB::table('m_kategori')->where('nama_kategori', 'SNK')->update(['deskripsi' => 'Camilan']);
    //     // return 'update data berhasil. Jumlah data yang di update :'.$row.' baris';

    //     // $row = DB::table('m_kategori')->where('nama_kategori', 'SNK')->delete();
    //     // return 'Delete berhasil. Jumlah data yang di hapus '.$row.' Baris';

    //     $data = DB::table('m_kategori')->get();
    //     return view('kategori', ['data'=>$data]);
    // }
}