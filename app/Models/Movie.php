<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'genre_id',
        'title',
        'review',
        'rating'
    ];

    /**
     * RELACIONAMENTO: Um filme pertence a um usuário
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * RELACIONAMENTO: Um filme pertence a um gênero
     */
    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }
}
