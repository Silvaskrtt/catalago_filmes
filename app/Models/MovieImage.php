<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'movie_id',
        'path',
        'filename',
        'mime_type',
        'size',
        'is_cover'
    ];

    /**
     * RELACIONAMENTO: Uma imagem pertence a um filme
     */
    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    /**
     * Acessor para URL completa da imagem
     */
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->path);
    }

    /**
     * Acessor para caminho físico do arquivo
     */
    public function getFilePathAttribute()
    {
        return storage_path('app/public/' . $this->path);
    }

    /**
     * Verifica se o arquivo existe
     */
    public function fileExists()
    {
        return file_exists($this->file_path);
    }
}
