<?php

namespace App\Http\Middleware;

use App\Models\Post;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// MIDDLEWARE UNTUK MEMASTIKAN YANG BISA UPDATE POST HANYA USER SI PEMBUAT
class PemilikPostingan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {   
        // CEK ID USER YANG SEDANG LOGIN
        $currentUserId =  Auth::user()->id;

        // CEK DATA POSTINGAN YANG DIAKSES BERDASARKAN ID
        $postById = Post::findOrFail($request->id);
        
        // CEGAH EDIT JIKA ID USER DAN AUTHOR ID TIDAK SAMA
        if($postById->author_id != $currentUserId) {
            return response()->json(['message' => 'data not found'], 404);
        }
      
        return $next($request);
    }
}
