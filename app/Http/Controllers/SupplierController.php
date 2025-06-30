<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplierModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SupplierController extends Controller
{
    // Menampilkan halaman daftar supplier
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Supplier',
            'list' => ['Home', 'Supplier']
        ];

        $page = (object) [
            'title' => 'Daftar supplier yang terdaftar dalam sistem'
        ];

        $activeMenu = 'supplier';
        $suppliers = SupplierModel::all(); // Ambil semua data supplier

        return view('supplier.index', compact('breadcrumb', 'page', 'activeMenu','suppliers'));
    }

    // Mengambil data supplier dalam bentuk JSON untuk DataTables
    public function list(Request $request)
    {
        $suppliers = SupplierModel::select('supplier_id', 'supplier_kode', 'supplier_nama', 'supplier_alamat', 'supplier_telp');

        return DataTables::of($suppliers)
            ->addIndexColumn()
            ->addColumn('aksi', function ($supplier) {
                // return '<a href="'.url('/supplier/' . $supplier->supplier_id).'" class="btn btn-info btn-sm">Detail</a> '
                //     .'<a href="'.url('/supplier/' . $supplier->supplier_id . '/edit').'" class="btn btn-warning btn-sm">Edit</a> '
                //     .'<form class="d-inline-block" method="POST" action="'. url('/supplier/'.$supplier->supplier_id).'">'
                //     . csrf_field() . method_field('DELETE') .
                //     '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                $btn  = '<button onclick="modalAction(\''.url('/supplier/' . $supplier->supplier_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/supplier/' . $supplier->supplier_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/supplier/' . $supplier->supplier_id . '/delete_ajax').'\')"  class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    // Menampilkan halaman form tambah supplier
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Supplier',
            'list' => ['Home', 'Supplier', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah supplier baru'
        ];

        $activeMenu = 'supplier';

        return view('supplier.create', compact('breadcrumb', 'page', 'activeMenu'));
    }

    // Menyimpan data supplier baru
    public function store(Request $request)
    {
        $request->validate([
            'supplier_kode' => 'required|unique:m_supplier,supplier_kode',
            'supplier_nama' => 'required',
            'supplier_alamat' => 'required',
            'supplier_telp' => 'required|numeric',
        ]);

        SupplierModel::create($request->all());

        return redirect('/supplier')->with('success', 'Supplier berhasil ditambahkan');
    }

    // Menampilkan detail supplier
    public function show($id)
    {
        $supplier = SupplierModel::findOrFail($id);

        $breadcrumb = (object) [
            'title' => 'Detail Supplier',
            'list' => ['Home', 'Supplier', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail Supplier'
        ];

        $activeMenu = 'supplier';

        return view('supplier.show', compact('breadcrumb', 'page', 'supplier', 'activeMenu'));
    }

    // Menampilkan halaman form edit supplier
    public function edit($id)
    {
        $supplier = SupplierModel::findOrFail($id);

        $breadcrumb = (object) [
            'title' => 'Edit Supplier',
            'list' => ['Home', 'Supplier', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit Supplier'
        ];

        $activeMenu = 'supplier';

        return view('supplier.edit', compact('breadcrumb', 'page', 'supplier', 'activeMenu'));
    }

    // Menyimpan perubahan data supplier
    public function update(Request $request, $id)
    {
        $request->validate([
            'supplier_kode' => 'required|unique:m_supplier,supplier_kode,' . $id . ',supplier_id',
            'supplier_nama' => 'required',
            'supplier_alamat' => 'required',
            'supplier_telp' => 'required|numeric',
        ]);

        $supplier = SupplierModel::findOrFail($id);
        $supplier->update($request->all());

        return redirect('/supplier')->with('success', 'Supplier berhasil diperbarui');
    }

    // Menghapus data supplier
    public function destroy($id)
    {
        SupplierModel::findOrFail($id)->delete();
        return redirect('/supplier')->with('success', 'Supplier berhasil dihapus');
    }

    // Menampilkan modal konfirmasi delete
    public function confirm_ajax(string $id)
    {
        $supplier = SupplierModel::find($id);
        return view('supplier.confirm_ajax', compact('supplier'));
    }

    // Menampilkan form tambah supplier (Ajax)
    public function create_ajax()
    {
        return view('supplier.create_ajax');
    }

    // Menyimpan data supplier (Ajax)
    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'supplier_nama' => 'required|string|max:100|unique:m_supplier,supplier_nama',
                'supplier_kontak' => 'nullable|string|max:50',
                'supplier_alamat' => 'nullable|string|max:255',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            SupplierModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data supplier berhasil disimpan'
            ]);
        }

        return redirect('/');
    }

    // Menampilkan form edit supplier (Ajax)
    public function edit_ajax(string $id)
    {
        $supplier = SupplierModel::find($id);
        return view('supplier.edit_ajax', compact('supplier'));
    }

    // Menyimpan perubahan data supplier (Ajax)
    public function update_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'supplier_nama' => 'required|string|max:100|unique:m_supplier,supplier_nama,'.$id.',supplier_id',
                'supplier_kontak' => 'nullable|string|max:50',
                'supplier_alamat' => 'nullable|string|max:255',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors(),
                ]);
            }

            $supplier = SupplierModel::find($id);
            if ($supplier) {
                $supplier->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diperbarui'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

    // Menghapus supplier (Ajax)
    public function delete_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $supplier = SupplierModel::find($id);
            if ($supplier) {
                $supplier->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }
    public function import()
    {
        return view('supplier.import'); // Membuat view supplier/import.blade.php
    }

    // Proses import data supplier via AJAX
    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Validasi file
            $rules = [
                'file_supplier' => ['required', 'mimes:xlsx', 'max:1024'] // max 1MB
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_supplier');

            // Membaca file Excel menggunakan PhpSpreadsheet
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();

            // Mengambil data sheet dalam bentuk array
            $data = $sheet->toArray(null, false, true, true); // hasil array ['A' => kode, 'B' => nama, 'C' => alamat, ...]

            $insert = [];

            // Memproses data kecuali baris pertama (header)
            if (count($data) > 1) {
                foreach ($data as $baris => $value) {
                    if ($baris > 1) { // Lewati baris header
                        if (!empty($value['A']) && !empty($value['B']) && !empty($value['C']) && !empty($value['D'])) { // Pastikan semua kolom ada isinya
                            $insert[] = [
                                'supplier_kode' => trim($value['A']),
                                'supplier_nama' => trim($value['B']),
                                'supplier_telp' => trim($value['C']),
                                'supplier_alamat'=> trim($value['D']),
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }
                    }
                }

                // Menyimpan data supplier ke database
                if (count($insert) > 0) {
                    SupplierModel::insertOrIgnore($insert); // Insert data tanpa duplikasi

                    return response()->json([
                        'status' => true,
                        'message' => 'Data supplier berhasil diimport'
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
    $suppliers = SupplierModel::select('supplier_kode', 'supplier_nama', 'supplier_telp', 'supplier_alamat')
        ->orderBy('supplier_kode')
        ->get();

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Header kolom
    $sheet->setCellValue('A1', 'No');
    $sheet->setCellValue('B1', 'Kode Supplier');
    $sheet->setCellValue('C1', 'Nama Supplier');
    $sheet->setCellValue('D1', 'Telepon');
    $sheet->setCellValue('E1', 'Alamat');

    $sheet->getStyle('A1:E1')->getFont()->setBold(true);

    $no = 1;
    $baris = 2;
    foreach ($suppliers as $supplier) {
        $sheet->setCellValue('A' . $baris, $no);
        $sheet->setCellValue('B' . $baris, $supplier->supplier_kode);
        $sheet->setCellValue('C' . $baris, $supplier->supplier_nama);
        $sheet->setCellValue('D' . $baris, $supplier->supplier_telp);
        $sheet->setCellValue('E' . $baris, $supplier->supplier_alamat);
        $baris++;
        $no++;
    }

    // Atur kolom agar otomatis menyesuaikan lebar isi
    foreach (range('A', 'E') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    $sheet->setTitle('Data Supplier');
    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
    $filename = 'Data_Supplier_' . date('Y-m-d_H-i-s') . '.xlsx';

    // Header untuk proses download
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

}