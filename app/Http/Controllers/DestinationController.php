<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DestinationController extends Controller
{
    /**
     * Process and optimize uploaded image to WebP format
     */
    private function processImage($uploadedFile): string
    {
        $filename = time() . '_' . Str::slug(pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME)) . '.webp';
        $path = public_path('images/' . $filename);
        $thumbPath = public_path('images/' . str_replace('.webp', '_thumb.webp', $filename));

        // Create image from uploaded file based on mime type
        $imageResource = match ($uploadedFile->getMimeType()) {
            'image/jpeg', 'image/jpg' => imagecreatefromjpeg($uploadedFile->getPathname()),
            'image/png' => imagecreatefrompng($uploadedFile->getPathname()),
            'image/gif' => imagecreatefromgif($uploadedFile->getPathname()),
            'image/webp' => imagecreatefromwebp($uploadedFile->getPathname()),
            default => throw new \Exception('Unsupported image type')
        };

        // Save full-size WebP with 85% quality
        imagewebp($imageResource, $path, 85);

        // Generate 300x300 thumbnail
        $originalWidth = imagesx($imageResource);
        $originalHeight = imagesy($imageResource);
        $thumbSize = 300;

        // Calculate thumbnail dimensions (maintain aspect ratio, crop to square)
        $size = min($originalWidth, $originalHeight);
        $x = ($originalWidth - $size) / 2;
        $y = ($originalHeight - $size) / 2;

        $thumb = imagecreatetruecolor($thumbSize, $thumbSize);
        imagecopyresampled($thumb, $imageResource, 0, 0, $x, $y, $thumbSize, $thumbSize, $size, $size);
        imagewebp($thumb, $thumbPath, 85);

        imagedestroy($imageResource);
        imagedestroy($thumb);

        return $filename;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $destinations = Destination::orderBy('id', 'desc')->get();

        return view('destinations.index', compact('destinations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('destinations.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|integer|exists:categories,id',
            'location' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048|dimensions:min_width=800,min_height=600',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $this->processImage($request->file('image'));
        }

        Destination::create($data);

        return redirect()->route('destinations.index')
            ->with('success', 'Destinasi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Destination $destination)
    {
        return view('destinations.show', compact('destination'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Destination $destination)
    {
        $categories = Category::orderBy('name')->get();
        return view('destinations.edit', compact('destination', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Destination $destination)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|integer|exists:categories,id',
            'location' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048|dimensions:min_width=800,min_height=600',
        ]);

        if ($request->hasFile('image')) {
            if ($destination->image && File::exists(public_path('images/' . $destination->image))) {
                File::delete(public_path('images/' . $destination->image));
                // Delete thumbnail if exists
                $thumbPath = str_replace('.webp', '_thumb.webp', $destination->image);
                if (File::exists(public_path('images/' . $thumbPath))) {
                    File::delete(public_path('images/' . $thumbPath));
                }
            }

            $data['image'] = $this->processImage($request->file('image'));
        }

        $destination->update($data);

        return redirect()->route('destinations.index')
            ->with('success', 'Destinasi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Destination $destination)
    {
        if ($destination->image && File::exists(public_path('images/' . $destination->image))) {
            File::delete(public_path('images/' . $destination->image));
            // Delete thumbnail if exists
            $thumbPath = str_replace('.webp', '_thumb.webp', $destination->image);
            if (File::exists(public_path('images/' . $thumbPath))) {
                File::delete(public_path('images/' . $thumbPath));
            }
        }

        $destination->delete();

        return redirect()->route('destinations.index')
            ->with('success', 'Destinasi berhasil dihapus.');
    }
}
