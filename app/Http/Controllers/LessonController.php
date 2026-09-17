<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Topic;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function index(Request $request)
    {
        $query = Lesson::with(['topic.course'])
            ->whereHas('topic.course')
            ->orderByDesc('lessons.id');

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($lessonQuery) use ($search) {
                $lessonQuery->where('lessons.title', 'ILIKE', "%{$search}%")
                    ->orWhereHas('topic', function ($topicQuery) use ($search) {
                        $topicQuery->where('title', 'ILIKE', "%{$search}%")
                            ->orWhereHas('course', fn ($courseQuery) => $courseQuery->where('title', 'ILIKE', "%{$search}%"));
                    });
            });
        }

        if ($request->filled('course_id')) {
            $query->whereHas('topic', fn ($topicQuery) => $topicQuery->where('course_id', $request->integer('course_id')));
        }

        if ($request->filled('topic_id')) {
            $query->where('topic_id', $request->integer('topic_id'));
        }

        $lessons = $query->paginate(15)->withQueryString();
        $courses = Course::query()->orderBy('title')->get(['id', 'title']);
        $topics = Topic::whereHas('course')->with('course')->orderBy('course_id')->orderBy('urutan')->get();

        return view('lessons.index', [
            'lessons' => $lessons,
            'courses' => $courses,
            'topics' => $topics,
            'lessonTotal' => Lesson::whereHas('topic.course')->count(),
            'topicTotal' => Topic::whereHas('course')->count(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'topic_id' => 'required|integer|exists:topics,id',
            'material_type' => 'required|string|max:100',
            'duration' => 'required|integer|min:0',
            'material_url' => 'required|string|max:65535',
        ]);

        Lesson::create($data);

        return redirect()->route('lessons.index')->with('success', 'Lesson berhasil ditambahkan.');
    }

    public function update(Request $request, Lesson $lesson)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'topic_id' => 'required|integer|exists:topics,id',
            'material_type' => 'required|string|max:100',
            'duration' => 'required|integer|min:0',
            'material_url' => 'required|string|max:65535',
        ]);

        $lesson->update($data);

        return redirect()->route('lessons.index')->with('success', 'Lesson berhasil diperbarui.');
    }

    public function destroy(Lesson $lesson)
    {
        $lesson->delete();

        return redirect()->route('lessons.index')->with('success', 'Lesson berhasil dihapus.');
    }
}
