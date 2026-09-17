<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\LearningPath;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TopicController extends Controller
{
    public function index(Request $request)
    {
        $nonWebinarCourse = function ($courseQuery) {
            $courseQuery->whereDoesntHave('courseType')
                ->orWhereHas('courseType', fn ($typeQuery) => $typeQuery->whereRaw('LOWER(title) <> ?', ['webinar']));
        };

        $query = Course::query()
            ->whereHas('topics')
            ->where($nonWebinarCourse)
            ->with([
                'learningPath:id,title',
                'learningPaths:id,title',
                'courseType:id,title',
                'topics' => fn ($topicQuery) => $topicQuery
                    ->with('lessons')
                    ->with(['quizzes' => fn ($quizQuery) => $quizQuery->withCount('attempts')])
                    ->orderBy('urutan')
                    ->orderBy('id'),
            ])
            ->withCount('topics')
            ->orderBy('title');

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($courseQuery) use ($search) {
                $courseQuery->where('courses.title', 'ILIKE', "%{$search}%")
                    ->orWhereHas('learningPath', fn ($pathQuery) => $pathQuery->where('title', 'ILIKE', "%{$search}%"))
                    ->orWhereHas('learningPaths', fn ($pathQuery) => $pathQuery->where('title', 'ILIKE', "%{$search}%"))
                    ->orWhereHas('topics', function ($topicQuery) use ($search) {
                        $topicQuery->where('title', 'ILIKE', "%{$search}%")
                            ->orWhereHas('lessons', fn ($lessonQuery) => $lessonQuery->where('title', 'ILIKE', "%{$search}%"))
                            ->orWhereHas('quizzes', fn ($quizQuery) => $quizQuery->where('title', 'ILIKE', "%{$search}%"));
                    });
            });
        }

        if ($request->filled('course_id')) {
            $query->whereKey($request->integer('course_id'));
        }

        if ($request->filled('learning_path_id')) {
            $learningPathId = $request->integer('learning_path_id');
            $query->where(function ($courseQuery) use ($learningPathId) {
                $courseQuery->where('learning_path_id', $learningPathId)
                    ->orWhereHas('learningPaths', fn ($pathQuery) => $pathQuery->whereKey($learningPathId));
            });
        }

        return view('topics.index', [
            'courses' => $query->paginate(8)->withQueryString(),
            'courseOptions' => Course::query()->where($nonWebinarCourse)->orderBy('title')->get(['id', 'title']),
            'topicOptions' => Topic::query()->whereHas('course', $nonWebinarCourse)->with('course')->orderBy('course_id')->orderBy('urutan')->get(),
            'learningPaths' => LearningPath::query()->orderBy('title')->get(['id', 'title']),
            'learningPathTotal' => LearningPath::count(),
            'topicTotal' => Topic::count(),
            'lessonTotal' => Lesson::count(),
            'quizTotal' => Quiz::count(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'course_id' => ['required', 'integer', Rule::exists('courses', 'id')->where(fn ($query) => $query->whereNull('deleted_at'))],
            'urutan' => 'required|integer|min:1',
        ]);

        if ($this->isWebinarCourse($data['course_id'])) {
            return back()->withInput()->withErrors(['course_id' => 'Course bertipe Webinar tidak dapat memiliki topic di halaman ini.']);
        }

        Topic::create($data);

        return redirect()->route('topics.index')->with('success', 'Topic berhasil ditambahkan.');
    }

    public function update(Request $request, Topic $topic)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'course_id' => ['required', 'integer', Rule::exists('courses', 'id')->where(fn ($query) => $query->whereNull('deleted_at'))],
            'urutan' => 'required|integer|min:1',
        ]);

        if ($this->isWebinarCourse($data['course_id'])) {
            return back()->withInput()->withErrors(['course_id' => 'Course bertipe Webinar tidak dapat memiliki topic di halaman ini.']);
        }

        $topic->update($data);

        return redirect()->route('topics.index')->with('success', 'Topic berhasil diperbarui.');
    }

    public function destroy(Topic $topic)
    {
        if ($topic->lessons()->exists()) {
            return redirect()->route('topics.index')->with('error', 'Topic tidak dapat dihapus karena masih memiliki lesson.');
        }

        $topic->delete();

        return redirect()->route('topics.index')->with('success', 'Topic berhasil dihapus.');
    }

    private function isWebinarCourse(int $courseId): bool
    {
        return Course::whereKey($courseId)
            ->whereHas('courseType', fn ($typeQuery) => $typeQuery->whereRaw('LOWER(title) = ?', ['webinar']))
            ->exists();
    }
}
