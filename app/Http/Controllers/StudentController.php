<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Classroom;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Student::with('classroom');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        $students = $query
            ->latest()
            ->paginate(10)
            ->appends($request->query());

        return view('management.students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classrooms = Classroom::all();

        return view('management.students.create', compact('classrooms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nisn' => 'required|unique:students,nisn|max:10',
            'name' => 'required|max:255',
            'classroom_id' => 'required|exists:classrooms,id',
        ]);

        Student::create($validated);

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Data siswa berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        $student->load('classroom');

        return view('management.students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        $classrooms = Classroom::all();

        return view('management.students.edit', compact('student', 'classrooms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'nisn' => 'required|max:10|unique:students,nisn,' . $student->id,
            'name' => 'required|max:255',
            'classroom_id' => 'required|exists:classrooms,id',
        ]);

        $student->update($validated);

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Data siswa berhasil dihapus.');
    }
}
