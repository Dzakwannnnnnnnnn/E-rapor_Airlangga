<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use Illuminate\Http\Request;

class AcademicYearsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = AcademicYear::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('year', 'like', "%{$search}%")
                  ->orWhere('semester', 'like', "%{$search}%");
            });
        }

        $academicYears = $query
            ->latest()
            ->paginate(10)
            ->appends($request->query());

        return view('management.academic_years.index', compact('academicYears', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        return view('management.academic_years.create', compact('activeYear'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|max:255',
            'semester' => 'required|in:genap,ganjil',
            'is_active' => 'required|boolean',
        ]);

        \DB::transaction(function () use ($validated) {
            if ($validated['is_active']) {
                AcademicYear::where('is_active', true)->update(['is_active' => false]);
            }
            AcademicYear::create($validated);
        });

        return redirect()
            ->route('admin.academic_years.index')
            ->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AcademicYear $academicYear)
    {
        $activeYear = AcademicYear::where('is_active', true)
            ->where('id', '!=', $academicYear->id)
            ->first();
        return view('management.academic_years.edit', compact('academicYear', 'activeYear'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AcademicYear $academicYear)
    {
        $validated = $request->validate([
            'year' => 'required|max:255',
            'semester' => 'required|in:genap,ganjil',
            'is_active' => 'required|boolean',
        ]);

        \DB::transaction(function () use ($validated, $academicYear) {
            if ($validated['is_active']) {
                AcademicYear::where('id', '!=', $academicYear->id)
                    ->where('is_active', true)
                    ->update(['is_active' => false]);
            }
            $academicYear->update($validated);
        });

        return redirect()
            ->route('admin.academic_years.index')
            ->with('success', 'Tahun ajaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcademicYear $academicYear)
    {
        $academicYear->delete();

        return redirect()
            ->route('admin.academic_years.index')
            ->with('success', 'Tahun ajaran berhasil dihapus.');
    }

    /**
     * Update the report release time for the active academic year.
     */
    public function updateReleaseTime(Request $request)
    {
        $validated = $request->validate([
            'report_release_at' => 'nullable|date',
        ]);

        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            return redirect()->back()->with('error', 'Tahun ajaran aktif tidak ditemukan.');
        }

        $activeYear->update([
            'report_release_at' => $validated['report_release_at'],
        ]);

        return redirect()->back()->with('success', 'Jadwal pembagian rapor berhasil diperbarui.');
    }
}
