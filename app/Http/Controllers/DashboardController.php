<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Destination;
use App\Models\User;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('permission:view-dashboard');
    }

    public function index()
    {
        $totalDestinations = Destination::count();
        $totalCategories = Category::count();
        $totalUsers = User::count();

        $recentDestinations = Destination::with('category')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalDestinations',
            'totalCategories',
            'totalUsers',
            'recentDestinations'
        ));
    }
}
