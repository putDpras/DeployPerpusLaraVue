<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Books;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Nette\Utils\Arrays;

class BooksController extends Controller
{
    public function __construct()
    {
        $this->middleware('isOwner')->except([
            'index',
            'show'
        ]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $books = Books::latest()->get();
        return response()->json([
            'message' => 'Data berhasil ditampilkan',
            'data' => $books
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'summary' => 'required|string',
            'image' => 'mimes:jpg,bmp,png',
            'stok' => 'required|integer',
            'category_id' => 'required'

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->hasFile('image')) {
           
            // $imageName = time() . '.' . $request->image->extension();
            // $request->image->storeAs('public/images', $imageName);
            // $path = env('APP_URL') . '/storage/images/';
            // $request->image = $path . $imageName;

            $imageName = time();

            $configCloudinary = new Configuration();
            $configCloudinary->cloud->cloudName = 'dsylmdhfs';
            $configCloudinary->cloud->apiKey = '548496764536754';
            $configCloudinary->cloud->apiSecret = '715VxjMxModkB9mkE8t89biQ-J0';
            $configCloudinary->url->secure = true;

            $cloudinary = new Cloudinary($configCloudinary);
            $result = $cloudinary->uploadApi()->upload($request->file('image')->getRealPath(), [
                "public_id" => $imageName
            ]);
        }

        $data = new Books;
        $data->title = $request->title;
        $data->summary = $request->summary;
        $data->image = $result['secure_url'];
        $data->stok = $request->stok;
        $data->category_id = $request->category_id;

        $data->save();
        return response()->json(
            [
                "Message" => "Data berhasil ditambahkan",
                "Result" => basename($result['url'])
            ],
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $book = Books::with(['categories', 'listBorrows'])->find($id);
        if (!$book) {
            return response()->json([
                "message" => 'Book tidak ditemukan'
            ], 404);
        }
        return response()->json([
            "message" => 'Data Detail ditampilkan',
            'data' => $book
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'summary' => 'required|string',
            'image' => 'mimes:jpg,bmp,png',
            'stok' => 'required|integer',
            'category_id' => 'required'

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $book = Books::find($id);
        if (!$book) {
            return response()->json([
                "message" => 'Book tidak ditemukan'
            ], 404);
        }

        if ($book->image) {
            // $imageName = basename($book->image);
            // Storage::delete('public/images/' . $imageName);
            $imageName = pathinfo($book->image, PATHINFO_FILENAME);

            $configCloudinary = new Configuration();
            $configCloudinary->cloud->cloudName = 'dsylmdhfs';
            $configCloudinary->cloud->apiKey = '548496764536754';
            $configCloudinary->cloud->apiSecret = '715VxjMxModkB9mkE8t89biQ-J0';
            $configCloudinary->url->secure = true;

            $cloudinary = new Cloudinary($configCloudinary);
            $result = $cloudinary->uploadApi()->destroy($imageName);
        }

        if ($request->hasFile('image')) {
            // $imageName = time() . '.' . $request->image->extension();
            // $request->image->storeAs('public/images', $imageName);
            // $path = env('APP_URL') . '/storage/images/';
            // $request->image = $path . $imageName;

            $imageName = time();

            $configCloudinary = new Configuration();
            $configCloudinary->cloud->cloudName = 'dsylmdhfs';
            $configCloudinary->cloud->apiKey = '548496764536754';
            $configCloudinary->cloud->apiSecret = '715VxjMxModkB9mkE8t89biQ-J0';
            $configCloudinary->url->secure = true;

            $cloudinary = new Cloudinary($configCloudinary);
            $result = $cloudinary->uploadApi()->upload($request->file('image')->getRealPath(), [
                "public_id" => $imageName
            ]);
        }


        $book->title = $request->title;
        $book->summary = $request->summary;
        $book->image = $result['secure_url'];
        $book->stok = $request->stok;
        $book->category_id = $request->category_id;

        $book->update();

        return response()->json(
            ["Message" => "Data berhasil diupdate"],
            201
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $book = Books::find($id);
        if (!$book) {
            return response()->json([
                "message" => 'Book tidak ditemukan'
            ], 404);
        }

        if ($book->image) {
            // $imageName = basename($book->image);
            // Storage::delete('public/images/' . $imageName);
            $imageName = pathinfo($book->image, PATHINFO_FILENAME);

            $configCloudinary = new Configuration();
            $configCloudinary->cloud->cloudName = 'dsylmdhfs';
            $configCloudinary->cloud->apiKey = '548496764536754';
            $configCloudinary->cloud->apiSecret = '715VxjMxModkB9mkE8t89biQ-J0';
            $configCloudinary->url->secure = true;

            $cloudinary = new Cloudinary($configCloudinary);
            $result = $cloudinary->uploadApi()->destroy($imageName);
        }

        $book->delete();
        return response()->json([
            "message" => 'Data berhasil dihapus'
        ], 200);
    }
}
