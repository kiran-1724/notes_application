<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class NoteController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): View 
    {
        $this->authorize('viewAny', Note::class);

        // Start building the query
        $query = Note::where('user_id', Auth::id());

        // 1. Handle Search Filter
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('content', 'like', "%{$searchTerm}%");
            });
        }

        // 2. Handle Sort Filter
        $sortOrder = $request->input('sort', 'newest'); // Default to 'newest'
        if ($sortOrder === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->latest(); // 'latest' is a shortcut for orderBy('created_at', 'desc')
        }

        // Execute the query with pagination
        $notes = $query->paginate(9)->withQueryString(); // withQueryString() is important!

        // Pass notes and filter values to the view
        return view('notes.index', [
            'notes' => $notes,
            'filters' => $request->only(['search', 'sort'])
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorize('create', Note::class);
        return view('notes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Note::class);

        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'content' => 'required|string',
        ]);

        $request->user()->notes()->create($validated);

        return Redirect::route('notes.index')->with('success', 'Note created successfully!');
    }
    
    /**
     * Display the specified resource.
     */
    public function show(Note $note): View
    {
        $this->authorize('view', $note);
        return view('notes.show', ['note' => $note]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Note $note): View
    {
        $this->authorize('update', $note);
        return view('notes.edit', ['note' => $note]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Note $note): RedirectResponse
    {
        $this->authorize('update', $note);

        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'content' => 'required|string',
        ]);

        $note->update($validated);

        return Redirect::route('notes.index')->with('success', 'Note updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note): RedirectResponse
    {
        $this->authorize('delete', $note);
        $note->delete();

        return Redirect::route('notes.index')->with('success', 'Note deleted successfully!');
    }
}