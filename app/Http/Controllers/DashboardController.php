<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        // Get the logged-in user's ID
        $userId = Auth::id();

        // 1. Get the total count of notes for the user
        $totalNotes = Note::where('user_id', $userId)->count();

        // 2. Get the 5 most recently UPDATED notes
        $recentNotes = Note::where('user_id', $userId)
                           ->latest('updated_at') // Sort by the 'updated_at' column
                           ->take(5) // Get only the top 5
                           ->get();

        // Pass the data to the dashboard view
        return view('dashboard', [
            'totalNotes' => $totalNotes,
            'recentNotes' => $recentNotes,
        ]);
    }
}