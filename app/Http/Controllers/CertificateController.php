<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function index(Request $request)
    {
        $query = Certificate::with(['user','course']);

        if ($request->search) {
            $query->where('user_id', 'like', '%' . $request->search . '%')
                  ->orWhere('course_id', 'like', '%' . $request->search . '%');
        }

        $certificates = $query->latest()->paginate(10);

        return view('certificate.index', compact('certificates'));
    }
}