<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);
    }
    public function updateOrCreateProfile(Request $request){
        $validator = Validator::make($request->all(), [
            'bio' => 'required|string',
            'age' => 'required|integer',
        ]);

        if ($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $user = auth()->user();

        $profile = Profile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'age' => $request->age,
                'bio' => $request->bio
            ]
            );
        
        return response()->json([
            'message' => 'Profile berhasil diubah',
            'user' => $profile
        ], 201);
        
    }
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     //

    // }

    // /**
    //  * Store a newly created resource in storage.
    //  */
    // public function store(Request $request)
    // {
    //     //
    // }

    // /**
    //  * Display the specified resource.
    //  */
    // public function show(string $id)
    // {
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(Request $request, string $id)
    // {
    //     //
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    // public function destroy(string $id)
    // {
    //     //
    // }
}
