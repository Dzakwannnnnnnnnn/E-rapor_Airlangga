<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Classroom::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('major', 'like', "%{$search}%");
            });
        }

        $classrooms = $query
            ->withCount('students') // opsional tapi sangat berguna
            ->latest()
            ->paginate(10)
            ->appends($request->query());

        return view('management.classrooms.index', compact('classrooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('management.classrooms.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|max:255',
            'major' => 'required|max:255',
        ]);

        Classroom::create($validated);

        return redirect()
            ->route('admin.classrooms.index')
            ->with('success', 'Data kelas berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Classroom $classroom)
    {
        $classroom->load('students');

        return view('management.classrooms.show', compact('classroom'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Classroom $classroom)
    {
        return view('management.classrooms.edit', compact('classroom'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Classroom $classroom)
    {
        $validated = $request->validate([
            'name'  => 'required|max:255',
            'major' => 'required|max:255',
        ]);

        $classroom->update($validated);

        return redirect()
            ->route('admin.classrooms.index')
            ->with('success', 'Data kelas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classroom $classroom)
    {
        // safety check (opsional tapi disarankan)
        if ($classroom->students()->count() > 0) {
            return redirect()
                ->route('admin.classrooms.index')
                ->with('error', 'Kelas tidak dapat dihapus karena masih memiliki siswa.');
        }

        $classroom->delete();

        return redirect()
            ->route('admin.classrooms.index')
            ->with('success', 'Data kelas berhasil dihapus.');
    }
}
