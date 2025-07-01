<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KategoriModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

class KategoriController extends Controller
{
    public function index(): Collection
    {
        return KategoriModel::all();
    }

    public function store(Request $request): JsonResponse
    {
        $kategori = KategoriModel::create($request->all());
        return response()->json($kategori, 201);
    }

    public function show(KategoriModel $kategori): KategoriModel
    {
        return $kategori;
    }

    public function update(Request $request, KategoriModel $kategori): KategoriModel
    {
        $kategori->update($request->all());
        return $kategori;
    }

    public function destroy(KategoriModel $kategori): JsonResponse
    {
        $kategori->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data kategori terhapus',
        ]);
    }
}
