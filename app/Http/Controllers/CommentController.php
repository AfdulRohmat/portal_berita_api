<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'post_id' => ['required', 'exists:posts,id'],
            'comments_content' => ['required'],
         ]);
        $request['user_id'] = auth()->user()->id;

        $comment = Comment::create($request->all());

        // loadMissing DIPAKAI UNTUK MENAMBAHKAN DATA DARI TABLE LAIN
        return new CommentResource($comment->loadMissing('comentator:id,email,username'));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $request->validate([
            'comments_content' => ['required'],
         ]);

        $comment = Comment::findOrFail($id);
        $comment->update($request->only('comments_content'));
 
         $data = [
             'status' => true,
             'message' => 'Data updated successfully',
         ];
 
         return (new CommentResource($comment->loadMissing('comentator:id,email,username')))->additional($data);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment -> delete();

        $data = [
            'status' => true,
            'message' => 'Data deleted successfully',
        ];

        return response()->json($data);
    }
}
