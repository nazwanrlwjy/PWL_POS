<?php
namespace App\Http\Controllers;

use App\Models\LevelModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    // Menampilkan halaman awal user
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar User',
            'list'  => ['Home', 'User']
        ];

        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];

        $activeMenu = 'user'; // set menu yang sedang aktif

        $level = LevelModel::all(); // ambil data level untuk ditampilkan di form

        return view('user.index', [
            'breadcrumb' => $breadcrumb, 
            'page' => $page, 
            'level' => $level,
            'activeMenu' => $activeMenu
        ]);
    }
    // Ambil data user dalam bentuk json untuk datatables 
    public function list(Request $request)
    {
    $users = UserModel::select('user_id', 'username', 'name', 'level_id')
    ->with('level');

    if ($request->level_id) {
    $users->where('level_id', $request->level_id);
    }
    
    return DataTables::of($users)
    // menambahkan kolom index / no urut (default name kolom: DT_RowIndex)
    ->addIndexColumn()  // menambahkan kolom index / no urut (default name kolom: DT_RowIndex) 
        ->addColumn('aksi', function ($user) {  // menambahkan kolom aksi 
            /* $btn  = '<a href="'.url('/user/' . $user->user_id).'" class="btn btn-info btn-sm">Detail</a> '; 
            $btn .= '<a href="'.url('/user/' . $user->user_id . '/edit').'" class="btn btn-warning btn-sm">Edit</a> '; 
            $btn .= '<form class="d-inline-block" method="POST" action="'. url('/user/'.$user->user_id).'">' 
                    . csrf_field() . method_field('DELETE') .  
                    '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakit menghapus data ini?\');">Hapus</button></form>';*/
            $btn  = '<button onclick="modalAction(\''.url('/user/' . $user->user_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
            $btn .= '<button onclick="modalAction(\''.url('/user/' . $user->user_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
            $btn .= '<button onclick="modalAction(\''.url('/user/' . $user->user_id . '/delete_ajax').'\')"  class="btn btn-danger btn-sm">Hapus</button> ';

            return $btn; 
        }) 
        ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html 
        ->make(true); 
}

    // Menampilkan halaman form tambah user
public function create()
{
    $breadcrumb = (object) [
        'title' => 'Tambah User',
        'list' => ['Home', 'User', 'Tambah']
    ];

    $page = (object) [
        'title' => 'Tambah user baru'
    ];

    $level = LevelModel::all(); // ambil data level untuk ditampilkan di form
    $activeMenu = 'user'; // set menu yang sedang aktif

    return view('user.create', [
        'breadcrumb' => $breadcrumb,
        'page' => $page,
        'level' => $level,
        'activeMenu' => $activeMenu
    ]);
}
    // Menyimpan data user baru
public function store(Request $request)
{
    $request->validate([
        // username harus diisi, berupa string, minimal 3 karakter, dan bernilai unik di tabel m_user kolom username
        'username' => 'required|string|min:3|unique:m_user,username',
        // name harus diisi, berupa string, dan maksimal 100 karakter
        'name' => 'required|string|max:100', 
        // password harus diisi dan minimal 5 karakter
        'password' => 'required|min:5', 
        // level_id harus diisi dan berupa angka
        'level_id' => 'required|integer' 
    ]);

    UserModel::create([
        'username' => $request->username,
        'name'     => $request->name,
        'password' => bcrypt($request->password), // password dienkripsi sebelum disimpan
        'level_id' => $request->level_id
    ]);

    return redirect('/user')->with('success', 'Data user berhasil disimpan');
}
    // Menampilkan detail user
public function show(string $id)
{
    $user = UserModel::with('level')->find($id);

    $breadcrumb = (object) [
        'title' => 'Detail User',
        'list'  => ['Home', 'User', 'Detail']
    ];

    $page = (object) [
        'title' => 'Detail User'
    ];

    $activeMenu = 'user'; // set menu yang sedang aktif

    return view('user.show', [
        'breadcrumb' => $breadcrumb,
        'page'       => $page,
        'user'       => $user,
        'activeMenu' => $activeMenu
    ]);
}
    // Menampilkan halaman form edit user
public function edit(string $id)
{
    $user  = UserModel::find($id);
    $level = LevelModel::all();

    $breadcrumb = (object) [
        'title' => 'Edit User',
        'list'  => ['Home', 'User', 'Edit']
    ];

    $page = (object) [
        'title' => 'Edit User'
    ];

    $activeMenu = 'user'; // set menu yang sedang aktif

    return view('user.edit', [
        'breadcrumb' => $breadcrumb,
        'page'       => $page,
        'user'       => $user,
        'level'      => $level,
        'activeMenu' => $activeMenu
    ]);
}

// Menyimpan perubahan data user
public function update(Request $request, string $id)
{
    $request->validate([
        // username harus diisi, berupa string, minimal 3 karakter,
        // dan bernilai unik di tabel m_user kolom username kecuali untuk user dengan id yang sedang diedit
        'username' => 'required|string|min:3|unique:m_user,username,'.$id.',user_id',
        'name'     => 'required|string|max:100', // name harus diisi, berupa string, dan maksimal 100 karakter
        'password' => 'nullable|min:5', // password bisa diisi (minimal 5 karakter) dan bisa tidak diisi
        'level_id' => 'required|integer' // level_id harus diisi dan berupa angka
    ]);

    UserModel::find($id)->update([
        'username' => $request->username,
        'name'     => $request->name,
        'password' => $request->password ? bcrypt($request->password) : UserModel::find($id)->password,
        'level_id' => $request->level_id
    ]);

    return redirect('/user')->with('success', 'Data user berhasil diubah');
}
    // Menghapus data user
public function destroy(string $id)
{
    $check = UserModel::find($id);
    if (!$check) { // untuk mengecek apakah data user dengan id yang dimaksud ada atau tidak
        return redirect('/user')->with('error', 'Data user tidak ditemukan');
    }

    try {
        UserModel::destroy($id); // Hapus data user

        return redirect('/user')->with('success', 'Data user berhasil dihapus');
    } catch (\Illuminate\Database\QueryException $e) {
        // Jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
        return redirect('/user')->with('error', 'Data user gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
    }
}
public function create_ajax()
{
    $level = LevelModel::select('level_id', 'level_name')->get();
    return view('user.create_ajax')->with('level', $level);
}
public function store_ajax(Request $request) {
    // cek apakah request berupa ajax
    if($request->ajax() || $request->wantsJson()){
        $rules = [
            'level_id' => 'required|integer',
            'username' => 'required|string|min:3|unique:m_user,username',
            'name' => 'required|string|max:100',
            'password' => 'required|min:6'
        ];

        // use Illuminate\Support\Facades\Validator;
        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return response()->json([
                'status' => false, // response status, false: error/gagal, true: berhasil
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors(), // pesan error validasi
            ]);
        }

        UserModel::create($request->all());
        return response()->json([
            'status' => true,
            'message' => 'Data user berhasil disimpan'
        ]);
    }

    redirect('/');
}
public function edit_ajax(string $id)
{
    $user = UserModel::find($id);
    $level = LevelModel::select('level_id', 'level_name')->get();
    return view('user.edit_ajax', ['user' => $user, 'level' => $level]);

}
public function update_ajax(Request $request, $id){
    // cek apakah request dari ajax
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'level_id' => 'required|integer',
            'username' => 'required|max:20|unique:m_user,username,'.$id.',user_id',
                        'name'     => 'required|max:100',
            'password' => 'nullable|min:6|max:20'
        ];
        
        // use Illuminate\Support\Facades\Validator;
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'   => false,    // respon json, true: berhasil, false: gagal
                'message'  => 'Validasi gagal.',
                'msgField' => $validator->errors()  // menunjukkan field mana yang error
            ]);
        }

        $check = UserModel::find($id);
        if ($check) {
            if(!$request->filled('password') ){ // jika password tidak diisi, maka hapus dari request
                $request->request->remove('password');
            }
            
            $check->update($request->all());
            return response()->json([
                'status'  => true,
                'message' => 'Data berhasil diupdate'
            ]);
        } else{
            return response()->json([
                'status'  => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }
    return redirect('/');
}
    public function confirm_ajax(string $id)
    {
        $user = UserModel::find($id);
        return view('user.confirm_ajax', ['user' => $user]);
    }
    public function delete_ajax(Request $request, $id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $user = UserModel::find($id);
            if ($user) {
                $user->delete();
                return response()->json([
                    'status'  => true,
                    'message' => 'Data berhasil dihapus'
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
    public function import()
        {
            return view('user.import');
        }
    public function import_ajax(Request $request)
{
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'file_user' => ['required', 'mimes:xlsx', 'max:1024'] // max 1MB
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors()
            ]);
        }

        $file = $request->file('file_user');

        $reader = IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();

        $data = $sheet->toArray(null, false, true, true);

        $insert = [];
        if (count($data) > 1) {
            foreach ($data as $baris => $value) {
                if ($baris > 1) {
                    // validasi data kosong pada baris
                    if (empty($value['A']) || empty($value['B']) || empty($value['C']) || empty($value['D'])) {
                        continue;
                    }

                    $insert[] = [
                        'level_id' => $value['A'],
                        'username' => $value['B'],
                        'name'     => $value['C'],
                        'password' => Hash::make($value['D']),
                        'created_at' => now()
                    ];
                }
            }

            if (count($insert) > 0) {
                // insert dengan pengecekan duplicate berdasarkan username
                foreach ($insert as $user) {
                    UserModel::firstOrCreate(
                        ['username' => $user['username']],
                        $user
                    );
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Data user berhasil diimport'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data valid untuk diimport'
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'message' => 'Data Excel kosong'
        ]);
    }

    return redirect('/');
}

    public function export_excel()
{
    $users = UserModel::select('level_id', 'username', 'name')
        ->with('level') // pastikan ada relasi level()
        ->orderBy('name')
        ->get();

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Header kolom
    $sheet->setCellValue('A1', 'No');
    $sheet->setCellValue('B1', 'Username');
    $sheet->setCellValue('C1', 'name');
    $sheet->setCellValue('D1', 'Level');

    $sheet->getStyle('A1:D1')->getFont()->setBold(true);

    $no = 1;
    $baris = 2;
    foreach ($users as $user) {
        $sheet->setCellValue('A' . $baris, $no);
        $sheet->setCellValue('B' . $baris, $user->username);
        $sheet->setCellValue('C' . $baris, $user->name);
        $sheet->setCellValue('D' . $baris, $user->level->level_name ?? '-');
        $baris++;
        $no++;
    }

    // Auto width kolom
    foreach (range('A', 'D') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    $sheet->setTitle('Data User');
    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
    $filename = 'Data_User_' . date('Y-m-d_H-i-s') . '.xlsx';

    // Header untuk download file
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: cache, must-revalidate');
    header('Pragma: public');

    $writer->save('php://output');
    exit;
}

public function export_pdf()
{
    ini_set('max_execution_time', 300); // Tambah waktu maksimal jadi 5 menit

    // Ambil data user dengan relasi level
    $users = UserModel::select('level_id', 'username', 'name')
        ->with('level') // pastikan ada relasi level()
        ->orderBy('name')
        ->get();

    // Pastikan data ditemukan
    if ($users->isEmpty()) {
        return response()->json(['message' => 'Tidak ada data untuk diekspor'], 404);
    }

    // Load view untuk PDF
    $pdf = Pdf::loadView('user.export_pdf', ['users' => $users]);

    // Atur paper size dan orientasi
    $pdf->setPaper('a4', 'portrait');

    // Pengaturan tambahan untuk memastikan gambar atau konten remote dapat dimuat
    $pdf->setOption('isHtml5ParserEnabled', true);
    $pdf->setOption('isPhpEnabled', true);

    // Render PDF dan stream
    return $pdf->stream('Data_User_' . date('Y-m-d_H-i-s') . '.pdf');
}
public function profile_page()
{
    $user = auth()->user();

    $breadcrumb = (object) [
        'title' => 'User Profile',
        'list' => ['Home', 'Profile']
    ];

    $page = (object) [
        'title' => 'User Profile'
    ];

    $activeMenu = 'profile';

    return view('user.profile', [
        'user' => $user, 
        'breadcrumb' => $breadcrumb, 
        'page' => $page, 
        'activeMenu' => $activeMenu
    ]);
}

public function update_picture(Request $request)
{
    // Validasi file
    $request->validate([
        'user_profile_picture' => 'required|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    try {
        $user = auth()->user();

        if (!$user) {
            return redirect('/login')->with('error', 'Silahkan login terlebih dahulu');
        }

        $userId = $user->user_id;
        $userModel = UserModel::find($userId);

        if (!$userModel) {
            return redirect('/login')->with('error', 'User tidak ditemukan');
        }

        // Menghapus foto jika sudah ada
        if ($userModel->user_profile_picture && file_exists(storage_path('app/public/' . $userModel->user_profile_picture))) {
            Storage::disk('public')->delete($userModel->user_profile_picture);
        }

        $fileName = 'profile_' . $userId . '_' . time() . '.' . $request->user_profile_picture->extension();
        $path = $request->user_profile_picture->storeAs('profiles', $fileName, 'public');

        UserModel::where('user_id', $userId)->update([
            'user_profile_picture' => $path
        ]);

        return redirect()->back()->with('success', 'Foto profile berhasil diperbarui');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Terjadi kesalahan');
    }
}


}