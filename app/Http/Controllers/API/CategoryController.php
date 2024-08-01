<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['isOwner'])->except([
            'show',
            'index'
        ]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $category = Categories::latest()->get();
        return response()->json([
            'message' => 'Berhasil Tampil semua Categories',
            'data' => $category
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'name' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $category = Categories::create($request->all());
        return response()->json([
            "message" => 'Berhasil tambah category'
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $category = Categories::with('books')->find($id);
        if (!$category) {
            return response()->json([
                "message" => 'Genre tidak ditemukan'
            ], 404);
        }

        return response()->json([
            "message" => 'Berhasil Detail data dengan id ' . $id,
            'data' => $category
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $category = Categories::find($id);
        if (!$category) {
            return response()->json([
                'message' => 'Category tidak ditemukan'
            ], 404);
        }

        $category->name = $request->name;
        $category->save();

        return response()->json([
            "message" => 'Berhasil melakukan update Category id : ' . $id
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $category = Categories::find($id);
        if (!$category) {
            return response()->json([
                "message" => 'Category tidak ditemukan'
            ], 404);
        }
        $category->delete();
        return response()->json([
            "message" => 'data dengan id : ' . $id . ' berhasil terhapus'
        ], 200);
    }
}
