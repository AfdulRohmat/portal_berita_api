<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

     // MENDEFINISIKAN FIELD TABLE MANA SAJA YANG AKAN DITAMPILKAN UNTUK USER/POSTMAN
     protected $fillable = [
        'post_id', 'user_id', 'comments_content'
    ];

    /**
     * Get the comentator that owns the Comment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function comentator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
