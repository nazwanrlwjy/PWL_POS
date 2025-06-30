<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory; 
use Barryvdh\DomPDF\Facade\Pdf;

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
            ->addColumn('aksi', function ($level) {
                // return '<a href="'.url('level/'.$row->level_id).'" class="btn btn-sm btn-info">Detail</a>
                //         <a href="'.url('level/'.$row->level_id.'/edit').'" class="btn btn-sm btn-warning">Edit</a>
                //         <form action="'.url('level/'.$row->level_id).'" method="POST" style="display:inline;">
                //             '.csrf_field().method_field("DELETE").'
                //             <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Yakin ingin menghapus?\')">Hapus</button>
                //         </form>';
                $btn  = '<button onclick="modalAction(\''.url('/level/' . $level->level_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/level/' . $level->level_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/level/' . $level->level_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
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
     public function create_ajax()
    {
        return view('level.create_ajax');
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_kode' => 'required|string|max:10|unique:m_level,level_kode',
                'level_name' => 'required|string|max:100'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            LevelModel::create($request->all());
            return response()->json([
                'status'  => true,
                'message' => 'Data level berhasil disimpan'
            ]);
        }
        return redirect('/');
    }
     public function edit_ajax(string $id)
    {
        $level = LevelModel::find($id);
        return view('level.edit_ajax', compact('level'));
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_kode' => 'required|string|max:10|unique:m_level,level_kode,'.$id.',level_id',
                'level_name' => 'required|string|max:100'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $level = LevelModel::find($id);
            if ($level) {
                $level->update($request->all());
                return response()->json([
                    'status'  => true,
                    'message' => 'Data level berhasil diperbarui'
                ]);
            }
            return response()->json([
                'status'  => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
        return redirect('/');
    }

    public function confirm_ajax(string $id)
    {
        $level = LevelModel::find($id);
        return view('level.confirm_ajax', compact('level'));
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $level = LevelModel::find($id);
            if ($level) {
                $level->delete();
                return response()->json([
                    'status'  => true,
                    'message' => 'Data level berhasil dihapus'
                ]);
            }
            return response()->json([
                'status'  => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
        return redirect('/');
    }

// Tampilkan halaman import
public function import()
{
    return view('level.import'); // buat file view bername level/import.blade.php
}

// Proses import data via AJAX
public function import_ajax(Request $request)
{
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'file_level' => ['required', 'mimes:xlsx', 'max:1024'] // max 1MB
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors()
            ]);
        }

        $file = $request->file('file_level');

        $reader = IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();

        $data = $sheet->toArray(null, false, true, true); // hasil array ['A' => kode, 'B' => name]

        $insert = [];

        if (count($data) > 1) {
            foreach ($data as $baris => $value) {
                if ($baris > 1) { // Lewati baris header
                    if (!empty($value['A']) && !empty($value['B'])) {
                        $insert[] = [
                            'level_kode' => trim($value['A']),
                            'level_name' => trim($value['B']),
                            'created_at' => now(),
                        ];
                    }
                }
            }

            if (count($insert) > 0) {
                LevelModel::insertOrIgnore($insert);

                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diimport'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data kosong atau tidak valid'
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Tidak ada data dalam file'
            ]);
        }
    }

    return redirect('/');
}

    public function export_excel()
{
    $levels = LevelModel::select('level_kode', 'level_name')
        ->orderBy('level_kode')
        ->get();

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Header kolom
    $sheet->setCellValue('A1', 'No');
    $sheet->setCellValue('B1', 'Kode Level');
    $sheet->setCellValue('C1', 'name Level');

    $sheet->getStyle('A1:C1')->getFont()->setBold(true);

    $no = 1;
    $baris = 2;
    foreach ($levels as $level) {
        $sheet->setCellValue('A' . $baris, $no);
        $sheet->setCellValue('B' . $baris, $level->level_kode);
        $sheet->setCellValue('C' . $baris, $level->level_name);
        $baris++;
        $no++;
    }

    // Auto width kolom
    foreach (range('A', 'C') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    $sheet->setTitle('Data Level');
    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
    $filename = 'Data_Level_' . date('Y-m-d_H-i-s') . '.xlsx';

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
    ini_set('max_execution_time', 300); // tambah waktu maksimal jadi 5 menit

    // Ambil data level dari database
    $levels = LevelModel::select('level_kode', 'level_name')
        ->orderBy('level_kode')
        ->get();

    // Buat PDF dari view
    $pdf = Pdf::loadView('level.export_pdf', ['levels' => $levels]);
    $pdf->setPaper('a4', 'portrait');
    $pdf->setOption(['isRemoteEnabled' => true]);
    $pdf->render();

    // Stream hasil PDF
    return $pdf->stream('Data_Level_' . date('Y-m-d_H-i-s') . '.pdf');
}


}