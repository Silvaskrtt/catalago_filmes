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

    /**
     * RELACIONAMENTO: Um filme pode ter várias imagens
     */
    public function images()
    {
        return $this->hasMany(MovieImage::class);
    }

    /**
     * RELACIONAMENTO: Um filme pode ter uma imagem de capa
     */
    public function coverImage()
    {
        return $this->hasOne(MovieImage::class)->where('is_cover', true);
    }

    /**
     * Acessor para URL da capa
     */
    public function getCoverUrlAttribute()
    {
        if ($this->coverImage) {
            return $this->coverImage->url;
        }

        // Retorna uma imagem padrão se não houver capa
        return asset('images/default-movie-cover.jpg');
    }

    /**
     * Verifica se o filme tem capa
     */
    public function hasCover()
    {
        return $this->coverImage()->exists();
    }
}
