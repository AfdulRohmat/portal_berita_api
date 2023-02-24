<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'image' => $this->image,
            'news_content' => $this->news_content,
            'author' => $this->whenLoaded('author'), // MEMAKAI EAGER LOADING (BEST PRACTICES)
            'total_comments' => $this->whenLoaded('comments', function () {
             return count($this->comments); // Menghitung jumlah komentar
            }), 
            'comments' => $this->whenLoaded('comments', function () {
                return collect($this->comments)->each(function ($comment) {
                    $comment->comentator;
                    return $comment;
                });
            }), // MEMAKAI EAGER LOADING (BEST PRACTICES)
            'created_at' => date_format($this->created_at, "d-m-Y H:i:s"),
        ];
    }
}
