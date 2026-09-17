<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Models\Course;
use App\Models\CourseType;
use App\Models\LearningPath;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Topic;
use App\Models\User;
use App\Models\Webinar;

class FlowController extends Controller
{
    public function index()
    {
        $nonWebinarCourse = function ($query) {
            $query->whereDoesntHave('courseType')
                ->orWhereHas('courseType', function ($typeQuery) {
                    $typeQuery->whereRaw('LOWER(title) <> ?', ['webinar']);
                });
        };

        $regularCourses = Course::query()->where($nonWebinarCourse);
        $webinarCourses = Course::query()->whereHas('courseType', function ($query) {
            $query->whereRaw('LOWER(title) = ?', ['webinar']);
        });

        $regularCourseCount = (clone $regularCourses)->count();
        $webinarCourseCount = (clone $webinarCourses)->count();
        $topicQuery = Topic::query()->whereHas('course', $nonWebinarCourse);
        $lessonQuery = Lesson::query()->whereHas('topic', function ($query) use ($nonWebinarCourse) {
            $query->whereHas('course', $nonWebinarCourse);
        });
        $quizQuery = Quiz::query()->whereHas('topic', function ($query) use ($nonWebinarCourse) {
            $query->whereHas('course', $nonWebinarCourse);
        });

        $studentQuery = User::query()->whereRaw('LOWER(role) = ?', ['student']);
        $webinarCourseIds = Webinar::query()
            ->whereHas('course')
            ->whereNotNull('course_id')
            ->pluck('course_id')
            ->unique();

        $completeTemplates = CertificateTemplate::query()
            ->where(function ($query) {
                $query->whereNull('course_id')->orWhereHas('course');
            })
            ->whereNotNull('course_id')
            ->whereNotNull('asset_id')
            ->whereHas('course', function ($query) {
                $query->whereNotNull('title')->where('title', '<>', '');
            })
            ->whereHas('asset', function ($query) {
                $query->whereNotNull('file_name')->where('file_name', '<>', '');
            });

        $metrics = [
            'learningPaths' => LearningPath::count(),
            'courses' => $regularCourseCount,
            'webinarCourses' => $webinarCourseCount,
            'topics' => (clone $topicQuery)->count(),
            'lessons' => (clone $lessonQuery)->count(),
            'quizzes' => (clone $quizQuery)->count(),
            'webinars' => Webinar::whereHas('course')->count(),
            'publishedWebinars' => Webinar::whereHas('course')->where('is_published', true)->count(),
            'users' => User::count(),
            'students' => (clone $studentQuery)->count(),
            'paidStudents' => (clone $studentQuery)->whereHas('memberships')->count(),
            'unpaidStudents' => (clone $studentQuery)->whereDoesntHave('memberships')->count(),
            'assets' => Asset::count(),
            'templateAssets' => Asset::where('bucket', 'certificate_templates')->count(),
            'templates' => CertificateTemplate::where(function ($query) {
                $query->whereNull('course_id')->orWhereHas('course');
            })->count(),
            'completeTemplates' => (clone $completeTemplates)->count(),
            'incompleteTemplates' => CertificateTemplate::where(function ($query) {
                $query->whereNull('course_id')->orWhereHas('course');
            })->count() - (clone $completeTemplates)->count(),
            'printedCertificates' => Certificate::whereHas('course')->count(),
            'printedCertificateUsers' => Certificate::whereHas('course')->distinct('user_id')->count('user_id'),
            'webinarCertificateUsers' => Certificate::query()
                ->whereIn('course_id', $webinarCourseIds)
                ->distinct('user_id')
                ->count('user_id'),
        ];

        $gaps = [
            'coursesWithoutTopics' => (clone $regularCourses)->whereDoesntHave('topics')->count(),
            'topicsWithoutContent' => (clone $topicQuery)
                ->whereDoesntHave('lessons')
                ->whereDoesntHave('quizzes')
                ->count(),
            'coursesWithoutTemplates' => (clone $regularCourses)->whereDoesntHave('certificateTemplates')->count(),
            'unlinkedWebinars' => Webinar::whereNull('course_id')->count(),
        ];

        $courseTypes = CourseType::query()
            ->withCount('courses')
            ->orderByDesc('courses_count')
            ->get();

        return view('flow.index', compact('metrics', 'gaps', 'courseTypes'));
    }
}
