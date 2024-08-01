<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class RolesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['isOwner']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $roles = Roles::latest()->get();
        return response()->json([
            'message' => 'Data berhasil ditampilkan',
            'data' => $roles
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(),[
            'name' => 'string|required|max:255'
        ]);
        if ($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $roles = Roles::create($request->all());
        return response()->json(
            ["Message" => "Data berhasil ditambahkan"],201);
    }

    /**
     * Display the specified resource.
     */
    // public function show(string $id)
    // {
        //
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $validator = Validator::make($request->all(),[
            'name' => 'string|required|max:255'
        ]);
        if ($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $role = Roles::find($id);
        if(!$role){
            return response()->json(
                ["Message" => "Data tidak ditemukan"]
                , 404);
        }
        $role->update($request->all());
        return response()->json(
            ["Message" => "Data berhasil Diupdate"]
            , 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $role = Roles::find($id);
        if(!$role){
            return response()->json(
                ["Message" => "Data tidak ditemukan"]
                , 404);
        }
        $role->delete();
        return response()->json(
            ["Message" => "Data berhasil Dihapus"]
            , 200);
    }
}
