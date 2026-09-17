<?php

namespace App\Http\Controllers;

use App\Models\Webinar;
use App\Models\Course;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WebinarController extends Controller
{
    /**
     * Tampilkan daftar webinar
     */
    public function index(Request $request)
    {
        $query = Webinar::with(['course.learningPath', 'course.certificates'])
            ->whereHas('course');

        // Filter & Search
        if ($request->filled('filter') && $request->filled('search')) {
            $filter = $request->filter;
            $search = $request->search;

            switch ($filter) {
                case 'course':
                    $query->whereHas('course', function ($q) use ($search) {
                        $q->where('title', 'ILIKE', "%{$search}%");
                    });
                    break;

                case 'published':
                    if (strtolower($search) === 'yes') {
                        $query->where('is_published', true);
                    } elseif (strtolower($search) === 'no') {
                        $query->where('is_published', false);
                    }
                    break;

                case 'meeting_link':
                    $query->where('meeting_link', 'ILIKE', "%{$search}%");
                    break;
            }
        }

        $webinars = $query
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->appends($request->only('filter', 'search'));

        $webinarCourseIds = (clone $query)
            ->whereNotNull('course_id')
            ->pluck('course_id')
            ->unique();

        $totalWebinars = (clone $query)->count();
        $publishedWebinars = (clone $query)->where('is_published', true)->count();
        $webinarsWithTemplates = (clone $query)
            ->whereHas('course.learningPath', function ($q) {
                $q->whereNotNull('certificate_url')
                    ->where('certificate_url', '<>', '');
            })
            ->count();
        $webinarsWithCertificates = Certificate::query()
            ->whereIn('course_id', $webinarCourseIds)
            ->where('type', 'Partisipasi')
            ->distinct('user_id')
            ->count('user_id');

        return view('webinars.index', compact(
            'webinars',
            'totalWebinars',
            'publishedWebinars',
            'webinarsWithTemplates',
            'webinarsWithCertificates'
        ));
    }

    /**
     * Simpan webinar baru
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'course_id'    => ['required', Rule::exists('courses', 'id')->where(fn ($query) => $query->whereNull('deleted_at'))],
            'total_seats'  => 'required|integer|min:1',
            'time_starts'   => 'required|date',
            'time_ends'    => 'required|date|after:time_starts',
            'meeting_link' => 'nullable|string',
            'passcode'     => 'nullable|string',
            'is_published' => 'required|boolean',
        ]);

        Webinar::create($request->all());

        return redirect()->route('webinars.index')->with('success', 'Webinar berhasil ditambahkan.');
    }

    /**
     * Update webinar
     */
    public function update(Request $request, Webinar $webinar)
    {
        $request->validate([
            'course_id'    => ['required', Rule::exists('courses', 'id')->where(fn ($query) => $query->whereNull('deleted_at'))],
            'total_seats'  => 'required|integer|min:1',
            'time_starts'   => 'required|date',
            'time_ends'    => 'required|date|after:time_starts',
            'meeting_link' => 'nullable|string',
            'passcode'     => 'nullable|string',
            'is_published' => 'required|boolean',
        ]);

        $webinar->update($request->all());

        return redirect()->route('webinars.index')->with('success', 'Webinar berhasil diperbarui.');
    }

    /**
     * Hapus webinar
     */
    public function destroy(Webinar $webinar)
    {
        $webinar->delete();
        return redirect()->route('webinars.index')->with('success', 'Webinar berhasil dihapus.');
    }

    public function search(Request $request)
    {
        $query = $request->get('q', '');

        $courses = \App\Models\Course::whereRaw('LOWER(title) LIKE ?', ['%' . strtolower($query) . '%'])
            ->select('id', 'title as text') // penting: title → text
            ->limit(10)
            ->get();

        return response()->json($courses);
    }
}
