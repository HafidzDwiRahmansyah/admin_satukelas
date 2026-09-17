<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Membership;
use App\Models\PaketMembership;
use App\Models\Course;
use App\Models\Certificate;
use Carbon\Carbon;

class StudentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX PAID STUDENTS (TIDAK DIUBAH)
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $query = User::whereRaw('LOWER(role) = ?', ['student'])
            ->whereHas('memberships')
            ->with([
                'memberships.paket:id,title',
            ])
            ->withCount('certificates');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ILIKE', "%{$search}%")
                    ->orWhere('email', 'ILIKE', "%{$search}%")
                    ->orWhere('company', 'ILIKE', "%{$search}%");
            });
        }

        if ($request->status === 'active') {
            $query->whereHas('memberships', fn($q) =>
                $q->where('expired_at', '>=', now())
            );
        }

        if ($request->status === 'expired') {
            $query->whereHas('memberships', fn($q) =>
                $q->where('expired_at', '<', now())
            );
        }

        if ($request->certification === 'passed') {
            $query->has('certificates');
        }

        if ($request->certification === 'not_passed') {
            $query->doesntHave('certificates');
        }

        if ($request->paket) {
            $query->whereHas('memberships', fn($q) =>
                $q->where('paket_membership_id', $request->paket)
            );
        }

        $paidStudentCount = (clone $query)->count();
        $activeStudentCount = (clone $query)
            ->whereHas('memberships', fn($q) => $q->where('expired_at', '>=', now()))
            ->count();
        $expiredStudentCount = (clone $query)
            ->whereHas('memberships', fn($q) => $q->where('expired_at', '<', now()))
            ->count();

        $students = $query
            ->latest()
            ->paginate(10)
            ->appends($request->only('search', 'status', 'certification', 'paket'));

        return view('students.paid.index', compact(
            'students',
            'paidStudentCount',
            'activeStudentCount',
            'expiredStudentCount'
        ));
    }

    public function unpaid(Request $request)
    {
        $query = User::whereRaw('LOWER(role) = ?', ['student'])
            ->whereDoesntHave('memberships')
            ->withCount('certificates');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ILIKE', "%{$search}%")
                    ->orWhere('email', 'ILIKE', "%{$search}%")
                    ->orWhere('company', 'ILIKE', "%{$search}%");
            });
        }

        $unpaidStudentCount = (clone $query)->count();
        $students = $query
            ->latest()
            ->paginate(10)
            ->appends($request->only('search'));

        return view('students.unpaid.index', compact('students', 'unpaidStudentCount'));
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW STUDENT (TIDAK DIUBAH)
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $student = User::with([
            'memberships.paket',
            'certificates.course'
        ])->where('role', 'student')
          ->findOrFail($id);

        $totalMembership = $student->memberships->count();
        $activeMembership = $student->memberships
            ->where('expired_at', '>=', now())
            ->count();

        $expiredMembership = $student->memberships
            ->where('expired_at', '<', now())
            ->count();

        $totalPassed = $student->certificates->count();

        return view('students.show', compact(
            'student',
            'totalMembership',
            'activeMembership',
            'expiredMembership',
            'totalPassed'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD (FULL FIXED & CLEAN)
    |--------------------------------------------------------------------------
    */

    public function dashboard()
    {
        $snapshot = DashboardSnapshot::find(1);

        if(!$snapshot){
            abort(404,'Dashboard analytics snapshot belum tersedia');
        }

        /*
        |--------------------------------------------------------------------------
        | COURSE STATISTICS PARSE
        |--------------------------------------------------------------------------
        */

        $courseStats = collect(json_decode($snapshot->course_stats ?? '[]'))
            ->map(function($row){

                return (object)[
                    'course'=>(object)[
                        'title'=>$row->title ?? '-'
                    ],
                    'participant'=>0,
                    'passed'=>$row->passed ?? 0,
                    'not_passed'=>0
                ];

            });

        /*
        |--------------------------------------------------------------------------
        | PACKAGE SUMMARY
        |--------------------------------------------------------------------------
        */

        $paketSummary = PaketMembership::withCount([
            'memberships as total_members',
            'memberships as active_members' => function($q){
                $q->where('expired_at','>=',now());
            },
            'memberships as expired_members' => function($q){
                $q->where('expired_at','<',now());
            }
        ])->get();

        return view('students.paid.dashboard',[
            'totalPaid'=>$snapshot->total_student,
            'totalActive'=>$snapshot->total_active,
            'totalExpired'=>$snapshot->total_expired,
            'totalPassed'=>$snapshot->total_passed,
            'totalNotPassed'=>$snapshot->total_not_passed,
            'courseNotPassedStats'=>$courseStats,
            'paketSummary'=>$paketSummary
        ]);
    }
}
