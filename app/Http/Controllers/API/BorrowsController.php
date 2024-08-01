<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Books;
use App\Models\Borrows;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BorrowsController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['auth:api'])->only(['updateOrCreateBorrow']);
        $this->middleware(['isOwner'])->only(['index']);
    }
    public function index(){
        $borrows = Borrows::latest()->get();
        return response()->json([
            'message' => 'Data berhasil ditampilkan',
            'data' => $borrows
        ]);
    }
    public function updateOrCreateBorrow(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'load_date' => 'required|date',
            'barrow_date' => 'required|date|after:load_date',
            'book_id' => 'required|exists:books,id'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $book = Books::find($request->book_id);
        if (!$book) {
            return response()->json([
                'message' => 'Book tidak ditemukan'
            ], 404);
        }

        $user = auth()->user();
        $borrow = Borrows::updateOrCreate(
            [
                'user_id' => $user->id,
                'book_id' => $request->book_id,

            ],
            [
                'barrow_date' => $request->barrow_date,
                'load_date' => $request->load_date,
                
            ]
        );

        


        return response()->json([
            "message" => "Boorows berhasil dibuat/diubah",
            // "user" => $checkReview2
            "borrow" => $borrow

        ], 201);
    }
}
