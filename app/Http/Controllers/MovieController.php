<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\MovieImage;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
    /**
     * Construtor do controller
     * Aplica middleware de autenticação
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $movies = Auth::user()->movies()->with(['genre', 'coverImage'])->latest()->get();
        $genres = Genre::all();

        return view('movies.index', compact('movies', 'genres'));
    }

     /**
     * Cria um novo gênero via AJAX
     */
    public function storeGenre(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:genres'
        ]);

        try {
            $genre = Genre::create([
                'name' => $request->name
            ]);

            return response()->json([
                'success' => true,
                'genre' => [
                    'id' => $genre->id,
                    'name' => $genre->name
                ],
                'message' => 'Gênero criado com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar gênero: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $genres = Genre::orderBy('name')->get(); // Ordenar por nome
        return view('movies.create', compact('genres'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'genre_id' => 'required|exists:genres,id',
            'review' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        $movie = Auth::user()->movies()->create($request->except('cover_image'));

        if ($request->hasFile('cover_image')) {
            $this->storeCoverImage($movie, $request->file('cover_image'));
        }

        return redirect()->route('movies.index')
            ->with('success', 'Filme adicionado com sucesso!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Movie $movie)
    {
        if ($movie->user_id !== Auth::id()) {
            abort(403, 'Você não tem permissão para editar este filme');
        }

        $genres = Genre::orderBy('name')->get(); // Ordenar por nome
        return view('movies.edit', compact('movie', 'genres'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Movie $movie)
    {
        if ($movie->user_id !== Auth::id()) {
            abort(403, 'Você não tem permissão para atualizar este filme');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'genre_id' => 'required|exists:genres,id',
            'review' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'remove_cover' => 'nullable|boolean'
        ]);

        $movie->update($request->except(['cover_image', 'remove_cover']));

        if ($request->has('remove_cover') && $request->remove_cover) {
            $this->removeCoverImage($movie);
        }

        if ($request->hasFile('cover_image')) {
            $this->storeCoverImage($movie, $request->file('cover_image'));
        }

        return redirect()->route('movies.index')
            ->with('success', 'Filme atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movie $movie)
    {
        if ($movie->user_id !== Auth::id()) {
            abort(403, 'Você não tem permissão para excluir este filme');
        }

        foreach ($movie->images as $image) {
            $this->deleteImageFile($image);
        }

        $movie->delete();

        return redirect()->route('movies.index')
            ->with('success', 'Filme excluído com sucesso!');
    }

    /**
     * Armazena a imagem de capa
     */
    private function storeCoverImage(Movie $movie, $imageFile)
    {
        // Remover capa anterior se existir
        $oldCover = $movie->coverImage;
        if ($oldCover) {
            $this->deleteImageFile($oldCover);
        }

        // Se for JPEG, converter para PNG
        $extension = strtolower($imageFile->getClientOriginalExtension());
        $isJpeg = in_array($extension, ['jpg', 'jpeg']);

        $filename = time() . '_' . uniqid() . ($isJpeg ? '.png' : '.' . $extension);
        $path = 'movie-covers/' . $filename;

        if ($isJpeg) {
            // Para JPEG, usar método especial que não depende do GD
            $this->handleJpegImage($imageFile, $path);
        } else {
            // Para PNG/GIF, usar GD normalmente
            $this->handleNonJpegImage($imageFile, $path);
        }

        // Criar registro no banco de dados
        $movie->images()->create([
            'path' => $path,
            'filename' => $imageFile->getClientOriginalName(),
            'mime_type' => $isJpeg ? 'image/png' : $imageFile->getMimeType(),
            'size' => Storage::disk('public')->size($path),
            'is_cover' => true
        ]);
    }

    /**
     * Lida com imagens JPEG (sem usar GD)
     */
    private function handleJpegImage($imageFile, $path)
    {
        // Método 1: Tentar usar o Intervention Image se disponível
        if (class_exists('Intervention\Image\ImageManager')) {
            try {
                $manager = new \Intervention\Image\ImageManager(
                    \Intervention\Image\Drivers\Gd\Driver::class
                );

                $image = $manager->read($imageFile);
                $image->scaleDown(400, 600);

                Storage::disk('public')->put($path, $image->toPng());
                return;
            } catch (\Exception $e) {
                // Fallback para método simples
            }
        }

        // Método 2: Usar biblioteca alternativa (simples)
        $this->convertJpegToPngSimple($imageFile, $path);
    }

    /**
     * Converte JPEG para PNG de forma simples
     */
    private function convertJpegToPngSimple($imageFile, $path)
    {
        $sourcePath = $imageFile->getPathname();
        $destinationPath = storage_path('app/public/' . $path);

        // Criar diretório
        Storage::disk('public')->makeDirectory('movie-covers');

        // Se não conseguirmos converter, pelo menos copiar
        if (!copy($sourcePath, $destinationPath)) {
            // Fallback final: usar o método store do Laravel
            $imageFile->storeAs('movie-covers', basename($path), 'public');
        }
    }

    /**
     * Lida com imagens não-JPEG (PNG, GIF)
     */
    private function handleNonJpegImage($imageFile, $path)
    {
        $sourcePath = $imageFile->getPathname();
        $mimeType = $imageFile->getMimeType();
        $destinationPath = storage_path('app/public/' . $path);

        // Criar diretório
        Storage::disk('public')->makeDirectory('movie-covers');

        // Verificar se podemos usar GD
        $canUseGd = false;

        if ($mimeType == 'image/png' && function_exists('imagecreatefrompng')) {
            $canUseGd = true;
        } elseif ($mimeType == 'image/gif' && function_exists('imagecreatefromgif')) {
            $canUseGd = true;
        }

        if ($canUseGd) {
            // Usar GD para redimensionar
            $this->resizeWithGd($imageFile, $destinationPath, $mimeType);
        } else {
            // Salvar sem redimensionar
            copy($sourcePath, $destinationPath);
        }
    }

    /**
     * Redimensiona imagem usando GD (apenas PNG/GIF)
     */
    private function resizeWithGd($imageFile, $destinationPath, $mimeType)
    {
        $sourcePath = $imageFile->getPathname();

        // Carregar imagem
        if ($mimeType == 'image/png') {
            $sourceImage = imagecreatefrompng($sourcePath);
        } else { // GIF
            $sourceImage = imagecreatefromgif($sourcePath);
        }

        if (!$sourceImage) {
            copy($sourcePath, $destinationPath);
            return;
        }

        // Obter dimensões
        $originalWidth = imagesx($sourceImage);
        $originalHeight = imagesy($sourceImage);

        // Calcular novas dimensões
        $maxWidth = 400;
        $maxHeight = 600;

        if ($originalWidth <= $maxWidth && $originalHeight <= $maxHeight) {
            $newWidth = $originalWidth;
            $newHeight = $originalHeight;
        } else {
            $ratio = $originalWidth / $originalHeight;

            if ($maxWidth / $maxHeight > $ratio) {
                $newWidth = $maxHeight * $ratio;
                $newHeight = $maxHeight;
            } else {
                $newWidth = $maxWidth;
                $newHeight = $maxWidth / $ratio;
            }

            $newWidth = (int) round($newWidth);
            $newHeight = (int) round($newHeight);
        }

        // Criar nova imagem
        $newImage = imagecreatetruecolor($newWidth, $newHeight);

        // Preservar transparência
        imagecolortransparent($newImage, imagecolorallocatealpha($newImage, 0, 0, 0, 127));
        imagealphablending($newImage, false);
        imagesavealpha($newImage, true);

        // Redimensionar
        imagecopyresampled(
            $newImage, $sourceImage,
            0, 0, 0, 0,
            $newWidth, $newHeight,
            $originalWidth, $originalHeight
        );

        // Salvar
        if ($mimeType == 'image/png') {
            imagepng($newImage, $destinationPath, 9);
        } else {
            imagegif($newImage, $destinationPath);
        }

        // Liberar memória
        imagedestroy($sourceImage);
        imagedestroy($newImage);
    }

    /**
     * Remove a imagem de capa
     */
    private function removeCoverImage(Movie $movie)
    {
        $cover = $movie->coverImage;
        if ($cover) {
            $this->deleteImageFile($cover);
        }
    }

    /**
     * Deleta o arquivo físico e registro do banco
     */
    private function deleteImageFile(MovieImage $image)
    {
        if (Storage::disk('public')->exists($image->path)) {
            Storage::disk('public')->delete($image->path);
        }
        $image->delete();
    }
}
