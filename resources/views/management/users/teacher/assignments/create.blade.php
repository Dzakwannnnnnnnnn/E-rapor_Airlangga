@extends('layouts.dashboard')

@section('title', 'Tambah Penugasan Mengajar - ' . $user->name . ' - e-Rapor')

@section('back_url', route('admin.teachers.assignments.index', $teacher->id))

@section('content')
<div class="-mx-4 -mt-4 md:-mx-8 md:-mt-8 w-[calc(100%+2rem)] md:w-[calc(100%+4rem)] overflow-hidden relative">

    <div class="bg-primary text-white pt-10 pb-20 px-6 md:px-8 relative overflow-hidden select-none">

        {{-- Background Geometric Decoration --}}
        <div class="absolute -top-12 -left-12 w-64 h-64 border-4 border-dashed border-white/10 rounded-full pointer-events-none z-0"></div>

        <div class="absolute top-6 right-[10%] opacity-25 grid grid-cols-6 gap-1.5 pointer-events-none z-0">
            @for ($i = 0; $i < 24; $i++)
                <div class="w-1.5 h-1.5 bg-white rounded-full"></div>
            @endfor
        </div>

        <div class="absolute top-1/2 left-[15%] w-12 h-12 border-[3px] border-solid border-secondary/40 rounded-full pointer-events-none z-0"></div>
        <div class="absolute top-4 right-[25%] w-72 h-72 bg-blue-500/10 rounded-full blur-2xl pointer-events-none z-0"></div>

        {{-- Hero Content --}}
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center md:items-start gap-6 relative z-10">
            <div class="relative shrink-0">
                <div class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-white text-primary flex items-center justify-center font-extrabold text-2xl md:text-3xl shadow-md border-4 border-white/20">
                    <i class="fa-solid fa-chalkboard-user"></i>
                </div>
            </div>

            <div class="flex-1 text-center md:text-left min-w-0 mt-1">
                <div class="inline-flex items-center gap-1.5 bg-secondary text-[10px] text-slate-950 font-bold px-2.5 py-0.5 rounded-full mb-2.5 uppercase tracking-wider shadow-sm">
                    <i class="fa-solid fa-id-card text-[9px]"></i> NIP: {{ $teacher->nip ?? '-' }}
                </div>
                <h3 class="font-extrabold text-xl md:text-3xl leading-tight uppercase truncate tracking-wide">Tambah Penugasan Mengajar</h3>
                <h4 class="text-sm md:text-base font-semibold text-white/90 mt-0.5 uppercase tracking-wide">{{ $user->name }}</h4>

                <div class="mt-3 flex flex-wrap justify-center md:justify-start gap-x-6 gap-y-1.5 text-xs md:text-sm text-white/85 font-medium">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-school text-base text-secondary opacity-95"></i>
                        <span>Panel Kontrol Kependidikan</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-calendar-days text-base text-secondary opacity-95"></i>
                        <span>Tahun Ajaran Aktif: {{ $activeYear->year ?? '-' }} ({{ ($activeYear->semester ?? 1) == 1 ? 'Ganjil' : 'Genap' }})</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bottom Wave Path --}}
        <div class="absolute bottom-0 inset-x-0 h-6 bg-secondary z-10" style="clip-path: polygon(0 100%, 100% 60%, 100% 100%, 0 100%);"></div>
        <div class="absolute bottom-0 inset-x-0 h-3 bg-bg z-10" style="clip-path: polygon(0 100%, 100% 30%, 100% 100%, 0 100%);"></div>
    </div>
</div>

<div class="max-w-2xl mx-auto mt-6 space-y-6 pb-12">

    {{-- Floating Back Button --}}
    <div class="-mt-16 mb-8 relative z-20 px-4 sm:px-0">
        <a href="{{ route('admin.teachers.assignments.index', $teacher->id) }}"
           class="flex sm:inline-flex items-center gap-3.5 bg-white rounded-2xl shadow-md border border-slate-100 p-3 hover:bg-slate-50 transition-colors group w-full sm:w-auto">
            <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0 transition-colors group-hover:bg-primary group-hover:text-white">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </div>
            <div class="flex flex-col justify-center min-w-0 pr-2">
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1 block">Navigasi</span>
                <span class="text-xs font-black text-slate-800 uppercase leading-none block truncate">Kembali ke Penugasan</span>
            </div>
        </a>
    </div>

    {{-- Form Container --}}
    <div class="px-4 sm:px-0 space-y-3">
        <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest pl-1">Form Plot Penugasan</h4>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 space-y-5">
            <form id="create-assignment-form" method="POST" action="{{ route('admin.teachers.assignments.store', $teacher->id) }}" class="space-y-4">
                @csrf

                {{-- Input Tahun Ajaran --}}
                <div class="space-y-1.5">
                    <label for="academic_year_id" class="block text-xs font-black text-slate-500 uppercase tracking-wider pl-1">Tahun Ajaran</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-calendar-days text-sm"></i>
                        </div>
                        <select name="academic_year_id" id="academic_year_id" required
                            class="w-full pl-11 pr-10 py-3.5 bg-slate-50/50 border @error('academic_year_id') border-danger @else border-slate-200 focus:border-primary @enderror rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:bg-white transition-all shadow-sm appearance-none cursor-pointer">
                            <option value="" disabled selected>Pilih Tahun Ajaran</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}" {{ $activeYear && $activeYear->id == $year->id ? 'selected' : '' }}>
                                    {{ $year->year }} — {{ $year->semester == 1 ? 'Ganjil' : 'Genap' }} {{ $activeYear && $activeYear->id == $year->id ? '(Aktif)' : '' }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                    @error('academic_year_id')
                        <p class="text-xs font-bold text-danger mt-1 uppercase tracking-wide flex items-center gap-1.5 pl-1">
                            <i class="fa-solid fa-circle-exclamation text-[10px]"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Input Kelas --}}
                <div class="space-y-1.5">
                    <label for="classroom_id" class="block text-xs font-black text-slate-500 uppercase tracking-wider pl-1">Kelas / Ruangan</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-school text-sm"></i>
                        </div>
                        <select name="classroom_id" id="classroom_id" required
                            class="w-full pl-11 pr-10 py-3.5 bg-slate-50/50 border @error('classroom_id') border-danger @else border-slate-200 focus:border-primary @enderror rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:bg-white transition-all shadow-sm appearance-none cursor-pointer">
                            <option value="" disabled selected>Pilih Kelas</option>
                            @foreach($classrooms as $classroom)
                                <option value="{{ $classroom->id }}" data-name="{{ $classroom->name }}">
                                    {{ $classroom->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                    @error('classroom_id')
                        <p class="text-xs font-bold text-danger mt-1 uppercase tracking-wide flex items-center gap-1.5 pl-1">
                            <i class="fa-solid fa-circle-exclamation text-[10px]"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Input Mata Pelajaran --}}
                <div class="space-y-1.5">
                    <label for="subject_id" class="block text-xs font-black text-slate-500 uppercase tracking-wider pl-1">Mata Pelajaran</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-book text-sm"></i>
                        </div>
                        <select name="subject_id" id="subject_id" required
                            class="w-full pl-11 pr-10 py-3.5 bg-slate-50/50 border @error('subject_id') border-danger @else border-slate-200 focus:border-primary @enderror rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:bg-white transition-all shadow-sm appearance-none cursor-pointer">
                            <option value="" disabled selected>Pilih Mata Pelajaran</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                    @error('subject_id')
                        <p class="text-xs font-bold text-danger mt-1 uppercase tracking-wide flex items-center gap-1.5 pl-1">
                            <i class="fa-solid fa-circle-exclamation text-[10px]"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Desktop-only action panel --}}
                <div class="hidden md:flex items-center justify-end gap-4 pt-4 pb-2">
                    <a href="{{ route('admin.teachers.assignments.index', $teacher->id) }}"
                       class="px-5 py-3 rounded-xl border border-slate-200 text-slate-500 text-xs font-black uppercase tracking-wider hover:bg-slate-50 transition-all text-center">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-6 py-3 rounded-xl bg-primary hover:bg-opacity-90 text-xs font-black text-white uppercase tracking-wider transition-all shadow-md shadow-primary/20 cursor-pointer">
                        Simpan Penugasan
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

@section('bottom_bar')
<div class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-slate-100 shadow-[0_-8px_24px_rgba(0,0,0,0.08)] px-4 py-3 pb-safe">
    <div class="flex items-center gap-3 max-w-lg mx-auto">
        <a href="{{ route('admin.teachers.assignments.index', $teacher->id) }}"
           class="flex-1 flex items-center justify-center gap-2 py-3 rounded-2xl border-2 border-slate-200 bg-white text-slate-600 font-bold text-xs uppercase tracking-wider hover:bg-slate-50 transition-all">
            <i class="fa-solid fa-xmark text-slate-400"></i>
            Batal
        </a>
        <button type="submit" form="create-assignment-form"
                class="flex-[2] flex items-center justify-center gap-2 py-3 rounded-2xl bg-gradient-to-br from-primary to-blue-800 text-white font-black text-xs uppercase tracking-wider shadow-lg shadow-primary/30 hover:shadow-primary/50 transition-all border-b-2 border-blue-900">
            <i class="fa-solid fa-floppy-disk"></i>
            Simpan Penugasan
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const academicYearSelect = document.getElementById('academic_year_id');
    const classroomSelect = document.getElementById('classroom_id');
    const subjectSelect = document.getElementById('subject_id');
    
    // Store all subject options
    const originalSubjects = Array.from(subjectSelect.options).slice(1).map(opt => ({
        value: opt.value,
        text: opt.text
    }));

    const usedAssignments = @json($usedAssignments);

    function updateSubjects() {
        const selectedYear = academicYearSelect.value;
        const selectedClass = classroomSelect.value;

        // Save selected value
        const previousValue = subjectSelect.value;

        // Reset subject dropdown
        subjectSelect.innerHTML = '<option value="" disabled selected>Pilih Mata Pelajaran</option>';

        if (!selectedClass || !selectedYear) {
            subjectSelect.disabled = true;
            return;
        }

        subjectSelect.disabled = false;

        // Filter used subjects for the selected class and academic year
        const usedSubjectIds = usedAssignments
            .filter(item => item.academic_year_id == selectedYear && item.classroom_id == selectedClass)
            .map(item => item.subject_id);

        // Populate subjects that are NOT used
        let hasAvailable = false;
        originalSubjects.forEach(subject => {
            if (!usedSubjectIds.includes(parseInt(subject.value))) {
                const opt = document.createElement('option');
                opt.value = subject.value;
                opt.textContent = subject.text;
                if (subject.value === previousValue) {
                    opt.selected = true;
                }
                subjectSelect.appendChild(opt);
                hasAvailable = true;
            }
        });

        if (!hasAvailable) {
            const opt = document.createElement('option');
            opt.value = "";
            opt.disabled = true;
            opt.selected = true;
            opt.textContent = "Semua mata pelajaran telah diisi untuk kelas ini";
            subjectSelect.appendChild(opt);
        }
    }

    function updateClassrooms() {
        const selectedYear = academicYearSelect.value;
        if (!selectedYear) return;

        Array.from(classroomSelect.options).forEach(opt => {
            if (opt.value === "") return; // Skip placeholder
            const classId = opt.value;
            
            // Get used subjects for this class and year
            const usedSubjectIds = usedAssignments
                .filter(item => item.academic_year_id == selectedYear && item.classroom_id == classId)
                .map(item => item.subject_id);
            
            // Check if all subjects are filled
            const originalName = opt.getAttribute('data-name');
            if (usedSubjectIds.length >= originalSubjects.length) {
                opt.disabled = true;
                opt.textContent = originalName + ' (Penuh / Sudah Terisi)';
                // If it was selected, deselect it
                if (classroomSelect.value === classId) {
                    classroomSelect.value = "";
                }
            } else {
                opt.disabled = false;
                opt.textContent = originalName;
            }
        });
    }

    academicYearSelect.addEventListener('change', function() {
        updateClassrooms();
        updateSubjects();
    });
    
    classroomSelect.addEventListener('change', updateSubjects);

    // Initial trigger
    updateClassrooms();
    updateSubjects();
});
</script>
@endpush
