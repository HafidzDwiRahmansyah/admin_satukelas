<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function index(Request $request)
    {
        $query = Certificate::with(['user', 'course'])
            ->whereHas('course');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('user_id', 'like', '%' . $request->search . '%')
                    ->orWhere('course_id', 'like', '%' . $request->search . '%');
            });
        }

        $certificates = $query
            ->latest()
            ->paginate(10)
            ->appends($request->only('search', 'type'));

        return view('certificate.index', compact('certificates'));
    }
}
