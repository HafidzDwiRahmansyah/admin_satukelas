<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Topic;
use App\Models\User;
use App\Models\Webinar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataAuditController extends Controller
{
    public function index(Request $request)
    {
        $nonEventCourse = function ($query) {
            $query->whereDoesntHave('courseType')
                ->orWhereHas('courseType', function ($typeQuery) {
                    $typeQuery->whereRaw('LOWER(title) NOT LIKE ?', ['%webinar%'])
                        ->whereRaw('LOWER(title) NOT LIKE ?', ['%konsult%'])
                        ->whereRaw('LOWER(title) NOT LIKE ?', ['%consult%']);
                });
        };

        $allCourses = Course::query();
        $regularCourses = Course::query()->where($nonEventCourse);
        $regularTopics = Topic::query()->whereHas('course', $nonEventCourse);
        $incompleteTemplateFilter = function ($query) {
            $query->where(function ($courseQuery) {
                $courseQuery->whereNull('course_id')->orWhereHas('course');
            })->where(function ($incompleteQuery) {
                $incompleteQuery->whereNull('course_id')
                    ->orWhereNull('asset_id')
                    ->orWhereHas('course', function ($courseQuery) {
                        $courseQuery->whereNull('title')->orWhere('title', '');
                    })
                    ->orWhereHas('asset', function ($assetQuery) {
                        $assetQuery->whereNull('file_name')->orWhere('file_name', '');
                    });
            });
        };

        $completedWithoutCertificate = DB::table('enroll_courses as enrollment')
            ->join('users as student_user', 'student_user.id', '=', 'enrollment.user_id')
            ->join('courses as course', 'course.id', '=', 'enrollment.course_id')
            ->leftJoin('course_types as course_type', 'course_type.id', '=', 'course.course_type_id')
            ->whereNull('course.deleted_at')
            ->whereRaw('LOWER(student_user.role) = ?', ['student'])
            ->where('enrollment.is_completed', true)
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('topics as topic')
                    ->join('lessons as lesson', 'lesson.topic_id', '=', 'topic.id')
                    ->whereColumn('topic.course_id', 'enrollment.course_id');
            })
            ->where(function ($query) {
                $query->whereNull('course_type.title')
                    ->orWhere(function ($typeQuery) {
                        $typeQuery->whereRaw('LOWER(course_type.title) NOT LIKE ?', ['%webinar%'])
                            ->whereRaw('LOWER(course_type.title) NOT LIKE ?', ['%konsult%'])
                            ->whereRaw('LOWER(course_type.title) NOT LIKE ?', ['%consult%']);
                    });
            })
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('certificates as certificate')
                    ->whereColumn('certificate.user_id', 'enrollment.user_id')
                    ->whereColumn('certificate.course_id', 'enrollment.course_id');
            })
            ->select([
                'enrollment.id as enrollment_id',
                'enrollment.user_id',
                'enrollment.course_id',
                'enrollment.updated_at as completed_at',
                'student_user.name as user_name',
                'student_user.email as user_email',
            'course.title as course_title',
                'course_type.title as course_type_title',
            ])
            ->selectRaw("CASE
                WHEN EXISTS (
                    SELECT 1 FROM certificate_template AS complete_template
                    LEFT JOIN assets AS complete_asset ON complete_asset.id = complete_template.asset_id
                    WHERE complete_template.course_id = enrollment.course_id
                      AND complete_template.asset_id IS NOT NULL
                      AND complete_asset.file_name IS NOT NULL
                      AND complete_asset.file_name <> ''
                      AND course.title IS NOT NULL
                      AND course.title <> ''
                ) THEN 'Template tersedia'
                WHEN EXISTS (
                    SELECT 1 FROM certificate_template AS any_template
                    WHERE any_template.course_id = enrollment.course_id
                ) THEN 'Template belum lengkap'
                ELSE 'Template belum ada'
            END AS template_status");

        $metrics = [
            'coursesWithoutTemplates' => (clone $allCourses)->whereDoesntHave('certificateTemplates')->count(),
            'incompleteTemplates' => (clone CertificateTemplate::query())->where($incompleteTemplateFilter)->count(),
            'coursesWithoutTopics' => (clone $regularCourses)->whereDoesntHave('topics')->count(),
            'topicsWithoutContent' => (clone $regularTopics)->whereDoesntHave('lessons')->whereDoesntHave('quizzes')->count(),
            'lessonsWithoutMaterial' => Lesson::whereHas('topic.course')->where(function ($query) {
                $query->whereNull('material_url')->orWhere('material_url', '');
            })->count(),
            'coursesWithoutInstructor' => (clone $regularCourses)->whereNull('instructor_id')->count(),
            'completedWithoutCertificateCases' => (clone $completedWithoutCertificate)->count(),
            'completedWithoutCertificateUsers' => (clone $completedWithoutCertificate)->distinct('user_id')->count('user_id'),
            'coursesWithoutLessons' => (clone $regularCourses)->whereDoesntHave('lessons')->count(),
            'unlinkedWebinars' => Webinar::whereNull('course_id')->count(),
            'draftWebinars' => Webinar::whereHas('course')->where('is_published', false)->count(),
            'printedCertificates' => Certificate::whereHas('course')->count(),
        ];

        $courseWithoutTemplates = (clone $allCourses)
            ->with('courseType:id,title')
            ->whereDoesntHave('certificateTemplates')
            ->latest('id')
            ->limit(8)
            ->get(['id', 'title', 'course_type_id']);

        $courseWithoutTopics = (clone $regularCourses)
            ->with('courseType:id,title')
            ->whereDoesntHave('topics')
            ->latest('id')
            ->limit(8)
            ->get(['id', 'title', 'course_type_id']);

        $topicWithoutContent = (clone $regularTopics)
            ->with('course:id,title')
            ->whereDoesntHave('lessons')
            ->whereDoesntHave('quizzes')
            ->orderBy('course_id')
            ->orderBy('urutan')
            ->limit(8)
            ->get(['id', 'title', 'course_id', 'urutan']);

        $incompleteTemplates = CertificateTemplate::query()
            ->with(['course:id,title', 'asset:id,file_name'])
            ->where($incompleteTemplateFilter)
            ->latest('id')
            ->limit(8)
            ->get();

        $completedWithoutCertificateCases = (clone $completedWithoutCertificate)
            ->orderByDesc('enrollment.updated_at')
            ->limit(12)
            ->get();

        $courseWithoutLessons = Course::query()
            ->where($nonEventCourse)
            ->with('courseType:id,title')
            ->whereDoesntHave('lessons')
            ->latest('id')
            ->limit(8)
            ->get(['id', 'title', 'course_type_id']);

        $unlinkedWebinars = Webinar::query()
            ->whereNull('course_id')
            ->latest('id')
            ->limit(8)
            ->get(['id', 'course_id', 'is_published', 'time_starts']);

        return view('data-audit.index', compact(
            'metrics',
            'courseWithoutTemplates',
            'courseWithoutTopics',
            'courseWithoutLessons',
            'topicWithoutContent',
            'incompleteTemplates',
            'completedWithoutCertificateCases',
            'unlinkedWebinars'
        ));
    }

    public function details(Request $request, string $section)
    {
        $perPage = min(max($request->integer('per_page', 100), 1), 100);
        $page = max($request->integer('page', 1), 1);
        $nonEventCourse = function ($query) {
            $query->whereDoesntHave('courseType')
                ->orWhereHas('courseType', function ($typeQuery) {
                    $typeQuery->whereRaw('LOWER(title) NOT LIKE ?', ['%webinar%'])
                        ->whereRaw('LOWER(title) NOT LIKE ?', ['%konsult%'])
                        ->whereRaw('LOWER(title) NOT LIKE ?', ['%consult%']);
                });
        };
        $incompleteTemplateFilter = function ($query) {
            $query->where(function ($courseQuery) {
                $courseQuery->whereNull('course_id')->orWhereHas('course');
            })->where(function ($incompleteQuery) {
                $incompleteQuery->whereNull('course_id')
                    ->orWhereNull('asset_id')
                    ->orWhereHas('course', fn ($courseQuery) => $courseQuery->whereNull('title')->orWhere('title', ''))
                    ->orWhereHas('asset', fn ($assetQuery) => $assetQuery->whereNull('file_name')->orWhere('file_name', ''));
            });
        };

        $group = function ($title, $query, $mapper) use ($perPage, $page) {
            $paginator = $query->paginate($perPage, ['*'], 'page', $page);

            return [
                'title' => $title,
                'total' => $paginator->total(),
                'page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'items' => $paginator->getCollection()->map($mapper)->values()->all(),
            ];
        };

        $groups = [];

        if ($section === 'certificate') {
            $groups[] = $group('Course tanpa template certificate', Course::with('courseType:id,title')->whereDoesntHave('certificateTemplates')->latest('id'), function ($course) {
                return ['title' => $course->title, 'meta' => $course->courseType->title ?? 'Umum', 'status' => 'Template belum ada', 'url' => route('courses.index', ['search' => $course->title, 'filter' => 'title'])];
            });
            $groups[] = $group('Template certificate belum lengkap', CertificateTemplate::with(['course:id,title', 'asset:id,file_name'])->where($incompleteTemplateFilter)->latest('id'), function ($template) {
                $issue = !$template->course_id || !$template->course?->title ? 'Course kosong' : (!$template->asset_id || !$template->asset?->file_name ? 'Asset kosong' : 'Perlu dicek');
                return ['title' => $template->course?->title ?? 'Nama course belum ada', 'meta' => 'Template #'.$template->id, 'status' => $issue, 'url' => route('assets.index')];
            });
            $groups[] = $group('Selesai 100% tanpa certificate', $this->completedWithoutCertificateQuery()->orderByDesc('enrollment.updated_at'), function ($case) {
                return ['title' => $case->user_name ?: 'Tanpa nama', 'meta' => $case->course_title.' - '.$case->template_status, 'status' => 'Certificate belum tercetak', 'secondary' => $case->template_status, 'url' => route('students.show', $case->user_id)];
            });
        } elseif ($section === 'structure') {
            $groups[] = $group('Course tanpa topic', Course::where($nonEventCourse)->latest('id'), function ($course) {
                return ['title' => $course->title, 'meta' => 'Topic belum ada', 'status' => 'Perlu disusun', 'url' => route('topics.index', ['course_id' => $course->id])];
            });
            $groups[] = $group('Topic tanpa lesson dan quiz', Topic::with('course:id,title')->whereHas('course', $nonEventCourse)->whereDoesntHave('lessons')->whereDoesntHave('quizzes')->orderBy('course_id')->orderBy('urutan'), function ($topic) {
                return ['title' => $topic->title, 'meta' => $topic->course?->title ?? 'Course tidak tersedia', 'status' => 'Belum ada isi', 'url' => route('topics.index', ['search' => $topic->title])];
            });
            $groups[] = $group('Course tanpa lesson', Course::with('courseType:id,title')->where($nonEventCourse)->whereDoesntHave('lessons')->latest('id'), function ($course) {
                return ['title' => $course->title, 'meta' => $course->courseType->title ?? 'Umum', 'status' => 'Lesson belum ada', 'url' => route('courses.index', ['search' => $course->title, 'filter' => 'title'])];
            });
        } elseif ($section === 'completion') {
            $groups[] = $group('Enrollment 100% tanpa certificate', $this->completedWithoutCertificateQuery()->orderByDesc('enrollment.updated_at'), function ($case) {
                return ['title' => $case->user_name ?: 'Tanpa nama', 'meta' => $case->course_title.' - '.($case->user_email ?: 'email kosong'), 'status' => '100% selesai', 'secondary' => $case->template_status, 'url' => route('students.show', $case->user_id)];
            });
        } elseif ($section === 'operations') {
            $groups[] = $group('Lesson tanpa material URL', Lesson::with('topic.course')->whereHas('topic.course')->where(function ($query) {
                $query->whereNull('material_url')->orWhere('material_url', '');
            })->latest('id'), function ($lesson) {
                return ['title' => $lesson->title, 'meta' => $lesson->topic?->course?->title ?? 'Course tidak tersedia', 'status' => 'Material kosong', 'url' => route('lessons.index', ['topic_id' => $lesson->topic_id])];
            });
            $groups[] = $group('Course tanpa instructor', Course::with('courseType:id,title')->where($nonEventCourse)->whereNull('instructor_id')->latest('id'), function ($course) {
                return ['title' => $course->title, 'meta' => $course->courseType->title ?? 'Umum', 'status' => 'Instructor kosong', 'url' => route('courses.index', ['search' => $course->title, 'filter' => 'title'])];
            });
            $groups[] = $group('Webinar tanpa course', Webinar::whereNull('course_id')->latest('id'), function ($webinar) {
                return ['title' => 'Webinar #'.$webinar->id, 'meta' => $webinar->time_starts?->format('d M Y, H:i') ?? 'Jadwal belum ada', 'status' => 'Course kosong', 'url' => route('webinars.index')];
            });
        } else {
            abort(404);
        }

        return response()->json(['section' => $section, 'per_page' => $perPage, 'groups' => $groups]);
    }

    private function completedWithoutCertificateQuery()
    {
        return DB::table('enroll_courses as enrollment')
            ->join('users as student_user', 'student_user.id', '=', 'enrollment.user_id')
            ->join('courses as course', 'course.id', '=', 'enrollment.course_id')
            ->leftJoin('course_types as course_type', 'course_type.id', '=', 'course.course_type_id')
            ->whereNull('course.deleted_at')
            ->whereRaw('LOWER(student_user.role) = ?', ['student'])
            ->where('enrollment.is_completed', true)
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))->from('topics as topic')->join('lessons as lesson', 'lesson.topic_id', '=', 'topic.id')->whereColumn('topic.course_id', 'enrollment.course_id');
            })
            ->where(function ($query) {
                $query->whereNull('course_type.title')->orWhere(function ($typeQuery) {
                    $typeQuery->whereRaw('LOWER(course_type.title) NOT LIKE ?', ['%webinar%'])->whereRaw('LOWER(course_type.title) NOT LIKE ?', ['%konsult%'])->whereRaw('LOWER(course_type.title) NOT LIKE ?', ['%consult%']);
                });
            })
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))->from('certificates as certificate')->whereColumn('certificate.user_id', 'enrollment.user_id')->whereColumn('certificate.course_id', 'enrollment.course_id');
            })
            ->select(['enrollment.id as enrollment_id', 'enrollment.user_id', 'enrollment.course_id', 'enrollment.updated_at as completed_at', 'student_user.name as user_name', 'student_user.email as user_email', 'course.title as course_title', 'course_type.title as course_type_title'])
            ->selectRaw("CASE
                WHEN EXISTS (
                    SELECT 1 FROM certificate_template AS complete_template
                    LEFT JOIN assets AS complete_asset ON complete_asset.id = complete_template.asset_id
                    WHERE complete_template.course_id = enrollment.course_id
                      AND complete_template.asset_id IS NOT NULL
                      AND complete_asset.file_name IS NOT NULL
                      AND complete_asset.file_name <> ''
                      AND course.title IS NOT NULL
                      AND course.title <> ''
                ) THEN 'Template tersedia'
                WHEN EXISTS (
                    SELECT 1 FROM certificate_template AS any_template
                    WHERE any_template.course_id = enrollment.course_id
                ) THEN 'Template belum lengkap'
                ELSE 'Template belum ada'
            END AS template_status");
    }
}
