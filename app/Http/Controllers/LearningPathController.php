<?php

namespace App\Http\Controllers;

use App\Models\LearningPath;
use Illuminate\Http\Request;

class LearningPathController extends Controller
{
    public function index()
    {
        $learningPaths = LearningPath::latest()->paginate(10);
        return view('learning_paths.index', compact('learningPaths'));
    }

    public function create()
    {
        return view('learning_paths.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'delivery_method_id' => 'nullable|integer',
            'title' => 'required|string|max:255',
            'thumbnail' => 'nullable|string|max:255',
            'price' => 'nullable|numeric',
            'description' => 'nullable|string',
            'objective' => 'nullable|string',
            'certificate_url' => 'nullable|string|max:255',
        ]);

        LearningPath::create($data);

        return redirect()->route('learning_paths.index')->with('success', 'Learning Path berhasil ditambahkan.');
    }

    public function edit(LearningPath $learningPath)
    {
        return view('learning_paths.edit', compact('learningPath'));
    }

    public function update(Request $request, LearningPath $learningPath)
    {
        $data = $request->validate([
            'delivery_method_id' => 'nullable|integer',
            'title' => 'required|string|max:255',
            'thumbnail' => 'nullable|string|max:255',
            'price' => 'nullable|numeric',
            'description' => 'nullable|string',
            'objective' => 'nullable|string',
            'certificate_url' => 'nullable|string|max:255',
        ]);

        $learningPath->update($data);

        return redirect()->route('learning_paths.index')->with('success', 'Learning Path berhasil diperbarui.');
    }

    public function destroy(LearningPath $learningPath)
    {
        $learningPath->delete();
        return redirect()->route('learning_paths.index')->with('success', 'Learning Path berhasil dihapus.');
    }
}
