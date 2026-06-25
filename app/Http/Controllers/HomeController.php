<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Destination;

class HomeController extends Controller
{
    public function index()
    {
        $featuredDestinations = Destination::with('category')
            ->latest()
            ->take(6)
            ->get();

        $categories = Category::withCount('destinations')
            ->having('destinations_count', '>', 0)
            ->get();

        return view('home.index', compact('featuredDestinations', 'categories'));
    }

    public function destinations()
    {
        $destinations = Destination::with('category')
            ->latest()
            ->paginate(12);

        $categories = Category::withCount('destinations')->get();

        return view('home.destinations', compact('destinations', 'categories'));
    }

    public function show(Destination $destination)
    {
        $destination->load('category');

        $relatedDestinations = Destination::where('category_id', $destination->category_id)
            ->where('id', '!=', $destination->id)
            ->take(3)
            ->get();

        return view('home.show', compact('destination', 'relatedDestinations'));
    }
}
