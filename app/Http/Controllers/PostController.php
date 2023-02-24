<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\PostDetailResource;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();

        // DENGAN PAKAI RESOURCE
        // loadMissing DIPAKAI UNTUK MENAMBAHKAN DATA DARI TABLE LAIN
        return PostDetailResource::collection($posts->loadMissing(['author:id,email,username', 'comments:id,post_id,user_id,comments_content']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // MEALUKAN VALIDATION FORM HARUS TERSISI
        $request->validate([
            'title' => ['required', 'max:255'],
            'news_content' => ['required'],
        ]);

        $fileImage = null;
        if ($request->imageInput) {
            // PROSES UPLOAD FILE
            $filename = $this->generateRandomString();
            $extension = $request->imageInput->extension();
            $fileImage = $filename . '.' . $extension;

            // UPLOAD IMAGE
            Storage::putFileAs('images', $request->imageInput, $fileImage);
        }
        $request['image'] = $fileImage;
        $request['author_id'] = Auth::user()->id;

        $post = Post::create($request->all());

        // loadMissing DIPAKAI UNTUK MENAMBAHKAN DATA DARI TABLE LAIN
        return new PostDetailResource($post->loadMissing('author'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // MEMANGGIL DATA COLUMN TABLE LAIN DARI FOREGIN KEY (TIDAK BOLEH PAKAI SPASI, PRIMARY KEY WAJIB DISERTAKAN)
        $posts = Post::findOrFail($id);
        // RETURN TANPA RSOURCE (BUKAN BEST PRACTICE)
        // return response()->json(['data' => $posts]);



        // RETURN SINGLE DATA DENGAN RESOURCE (BEST PRACTICE)
        // loadMissing DIPAKAI UNTUK MENAMBAHKAN DATA DARI TABLE LAIN
        return new PostDetailResource($posts->loadMissing(['author:id,email,username', 'comments:id,post_id,user_id,comments_content']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // MEALUKAN VALIDATION FORM HARUS TERSISI
        $request->validate([
            'title' => ['required', 'max:255'],
            'news_content' => ['required'],
        ]);

        $post = Post::findOrFail($id);
        $post->update($request->all());

        $data = [
            'status' => true,
            'message' => 'Data updated successfully',
        ];

        return (new PostDetailResource($post->loadMissing('author:id,email,username,firstname,lastname')))->additional($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        $data = [
            'status' => true,
            'message' => 'Data deleted successfully',
        ];

        return response()->json($data);
    }

    // CUSTOM FUNCTION
    function generateRandomString($length = 20)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
