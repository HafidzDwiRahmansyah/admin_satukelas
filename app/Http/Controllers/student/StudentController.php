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
        $query = User::where('role', 'student')
            ->whereHas('memberships')
            ->with([
                'memberships.paket:id,title',
            ])
            ->withCount('certificates');

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

        $students = $query->latest()->paginate(10);

        return view('students.paid.index', compact('students'));
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