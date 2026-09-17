<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    public function store(Request $request)
    {
        Quiz::create($this->validatedData($request));

        return redirect()->route('topics.index')->with('success', 'Quiz berhasil ditambahkan.');
    }

    public function update(Request $request, Quiz $quiz)
    {
        $quiz->update($this->validatedData($request));

        return redirect()->route('topics.index')->with('success', 'Quiz berhasil diperbarui.');
    }

    public function destroy(Quiz $quiz)
    {
        $hasAttempts = $quiz->attempts()->exists();
        $hasWeightConfiguration = DB::table('bobot_quizzes')->where('quiz_id', $quiz->id)->exists();
        $hasQuestions = DB::table('quiz_question')->where('quiz_id', $quiz->id)->exists();

        if ($hasAttempts || $hasWeightConfiguration || $hasQuestions) {
            return redirect()->route('topics.index')->with('error', 'Quiz tidak dapat dihapus karena masih digunakan oleh attempt, konfigurasi bobot, atau pertanyaan.');
        }

        $quiz->delete();

        return redirect()->route('topics.index')->with('success', 'Quiz berhasil dihapus.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'topic_id' => 'required|integer|exists:topics,id',
            'duration' => 'required|integer|min:0',
            'passing_grade' => 'required|numeric|min:0|max:100',
        ]);
    }
}
