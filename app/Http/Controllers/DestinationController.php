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
            $image = $request->file('image');
            $filename = time() . '_' . Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $image->extension();
            $image->move(public_path('images'), $filename);
            $data['image'] = $filename;
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
            }

            $image = $request->file('image');
            $filename = time() . '_' . Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $image->extension();
            $image->move(public_path('images'), $filename);
            $data['image'] = $filename;
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
        }

        $destination->delete();

        return redirect()->route('destinations.index')
            ->with('success', 'Destinasi berhasil dihapus.');
    }
}
