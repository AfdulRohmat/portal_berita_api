<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'post_id' => $this->post_id,
            'user_id' => $this->user_id,
            'comments_content' => $this->comments_content,
            'commentator' => $this->whenLoaded('comentator'), // MEMAKAI EAGER LOADING (BEST PRACTICES)
            'created_at' => date_format($this->created_at, "d-m-Y H:i:s"),
        ];
    }
}
