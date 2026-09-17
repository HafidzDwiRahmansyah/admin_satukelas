<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\LearningPath;
use App\Models\CourseType;
use App\Models\User;
use Illuminate\Http\Request;
use App\Exports\CoursesExport;
use Maatwebsite\Excel\Facades\Excel;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $showDeleted = $request->boolean('trashed');
        $query = Course::with(['learningPath', 'courseType', 'instructor']);

        if ($showDeleted) {
            $query->onlyTrashed();
        }

        // Kalau ada filter & keyword
        if ($request->filled('filter') && $request->filled('search')) {
            $filter = $request->input('filter');
            $search = $request->input('search');

            switch ($filter) {
                case 'title':
                    $query->where('title', 'ILIKE', "%{$search}%");
                    break;

                case 'learning_path':
                    $query->whereHas('learningPath', function ($q) use ($search) {
                        $q->where('title', 'ILIKE', "%{$search}%");
                    });
                    break;

                case 'course_type':
                    $query->whereHas('courseType', function ($q) use ($search) {
                        $q->where('title', 'ILIKE', "%{$search}%");
                    });
                    break;

                case 'instructor':
                    $query->whereHas('instructor', function ($q) use ($search) {
                        $q->where('name', 'ILIKE', "%{$search}%");
                    });
                    break;
            }
        }

        if ($request->filled('course_type_id')) {
            $query->where('course_type_id', $request->integer('course_type_id'));
        }

        $courses = $query
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->only('filter', 'search', 'course_type_id'));

        $learningPaths = LearningPath::all();
        $courseTypes   = CourseType::query()
            ->withCount(['courses' => function ($courseQuery) use ($showDeleted) {
                if ($showDeleted) {
                    $courseQuery->onlyTrashed();
                }
            }])
            ->orderBy('title')
            ->get();
        $instructors   = User::where('role', 'instructor')->get();
        $trashedCourseCount = Course::onlyTrashed()->count();

        return view('courses.index', compact('courses', 'learningPaths', 'courseTypes', 'instructors', 'showDeleted', 'trashedCourseCount'));
    }

    public function trashed(Request $request)
    {
        $request->merge(['trashed' => '1']);

        return $this->index($request);
    }

    public function restore(int $course)
    {
        $deletedCourse = Course::onlyTrashed()->findOrFail($course);
        $deletedCourse->restore();

        return redirect()->route('courses.trashed')->with('success', 'Course berhasil dipulihkan ke daftar umum.');
    }

    public function create()
    {
        $learningPaths = LearningPath::all();
        $courseTypes = CourseType::all();
        $instructors = User::where('role', 'instructor')->get();

        return view('courses.create', compact('learningPaths', 'courseTypes', 'instructors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'learning_path_id' => 'required|exists:learning_paths,id',
            'course_type_id'   => 'required|exists:course_types,id',
            'title'            => 'required|string|max:255',
            'thumbnail'        => 'nullable|string',
            'price'            => 'required|numeric',
            'description'      => 'nullable|string',
            'instructor_id'    => 'required|exists:users,id',
            'objective'        => 'nullable|string',
            'is_favorit'       => 'boolean',
        ]);

        Course::create($request->all());

        return redirect()->route('courses.index')->with('success', 'Course berhasil ditambahkan.');
    }

    public function edit(Course $course)
    {
        $learningPaths = LearningPath::all();
        $courseTypes = CourseType::all();
        $instructors = User::where('role', 'instructor')->get();

        return view('courses.edit', compact('course', 'learningPaths', 'courseTypes', 'instructors'));
    }

    public function update(Request $request, Course $course)
    {
        $request->validate([
            'learning_path_id' => 'required|exists:learning_paths,id',
            'course_type_id'   => 'required|exists:course_types,id',
            'title'            => 'required|string|max:255',
            'thumbnail'        => 'nullable|string',
            'price'            => 'required|numeric',
            'description'      => 'nullable|string',
            'instructor_id'    => 'required|exists:users,id',
            'objective'        => 'nullable|string',
            'is_favorit'       => 'boolean',
        ]);

        $course->update($request->all());

        return redirect()->route('courses.index')->with('success', 'Course berhasil diperbarui.');
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('courses.index')->with('success', 'Course berhasil dihapus.');
    }

    public function show(Course $course)
    {
        return redirect()->route('courses.index');
    }

    public function export()
    {
        return Excel::download(new CoursesExport, 'courses.xlsx');
    }
}
