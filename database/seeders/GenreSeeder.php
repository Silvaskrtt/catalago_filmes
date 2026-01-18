<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
{
    public function run()
    {
        $genres = [
            ['name' => 'Ação'],
            ['name' => 'Comédia'],
            ['name' => 'Terror'],
            ['name' => 'Drama'],
            ['name' => 'Ficção Científica'],
            ['name' => 'Romance'],
            ['name' => 'Suspense'],
            ['name' => 'Animação'],
        ];

        foreach ($genres as $genre) {
            Genre::create($genre);
        }
    }
}
