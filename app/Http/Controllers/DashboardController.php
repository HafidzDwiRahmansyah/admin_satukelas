<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\LearningPath;
use App\Models\Membership;
use App\Models\User;
use App\Models\UserActivity;
use App\Models\Webinar;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $webinarCourseIds = Webinar::query()
            ->whereHas('course')
            ->whereNotNull('course_id')
            ->pluck('course_id')
            ->unique();

        $totalUsers = User::count();
        $totalStudents = User::whereRaw('LOWER(role) = ?', ['student'])->count();
        $totalAdmins = User::whereRaw('LOWER(role) IN (?, ?)', ['admin', 'superadmin'])->count();
        $totalInstructors = User::whereRaw('LOWER(role) = ?', ['instructor'])->count();
        $activeUsers = UserActivity::query()->distinct('user_id')->count('user_id');

        $totalLearningPaths = LearningPath::count();
        $totalCourses = Course::count();
        $totalWebinars = Webinar::whereHas('course')->count();
        $publishedWebinars = Webinar::whereHas('course')->where('is_published', true)->count();
        $totalCertificates = Certificate::whereHas('course')->count();
        $webinarCertificateUsers = Certificate::query()
            ->whereIn('course_id', $webinarCourseIds)
            ->where('type', 'Partisipasi')
            ->distinct('user_id')
            ->count('user_id');
        $totalAssets = Asset::count();
        $totalMemberships = Membership::count();

        $roleBreakdown = User::query()
            ->select('role', DB::raw('count(*) as total'))
            ->groupBy('role')
            ->orderByDesc('total')
            ->get();

        $topLearningPaths = LearningPath::withCount('courses')
            ->orderByDesc('courses_count')
            ->orderBy('title')
            ->limit(5)
            ->get();

        $recentWebinars = Webinar::with('course')
            ->whereHas('course')
            ->latest('created_at')
            ->limit(5)
            ->get();

        $recentUsers = User::query()
            ->latest('created_at')
            ->limit(5)
            ->get(['id', 'name', 'email', 'role', 'created_at']);

        $recentActivities = UserActivity::with('user')
            ->latest('last_activity')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalUsers',
            'totalStudents',
            'totalAdmins',
            'totalInstructors',
            'activeUsers',
            'totalLearningPaths',
            'totalCourses',
            'totalWebinars',
            'publishedWebinars',
            'totalCertificates',
            'webinarCertificateUsers',
            'totalAssets',
            'totalMemberships',
            'roleBreakdown',
            'topLearningPaths',
            'recentWebinars',
            'recentUsers',
            'recentActivities'
        ));
    }
}
