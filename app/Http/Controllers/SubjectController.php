<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Subject::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%");
            });
        }

        $subjects = $query
            ->latest()
            ->paginate(10)
            ->appends($request->query());

        return view('management.subjects.index', compact('subjects', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('management.subjects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'type' => 'required|in:academic,extracurricular',
        ]);

        Subject::create($validated);

        return redirect()
            ->route('admin.subjects.index')
            ->with('success', 'Data mata pelajaran berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $subject)
    {
        return view('management.subjects.edit', compact('subject'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'type' => 'required|in:academic,extracurricular',
        ]);

        $subject->update($validated);

        return redirect()
            ->route('admin.subjects.index')
            ->with('success', 'Data mata pelajaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject)
    {
        $subject->delete();

        return redirect()
            ->route('admin.subjects.index')
            ->with('success', 'Data mata pelajaran berhasil dihapus.');
    }
}
