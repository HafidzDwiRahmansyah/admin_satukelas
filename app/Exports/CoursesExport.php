<?php

namespace App\Exports;

use App\Models\Course;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CoursesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Course::with(['instructor', 'courseType'])
            ->where('course_type_id', 9) // 🔥 filter type = 1
            ->get()
            ->map(function ($course) {
                return [
                    'course_type' => $course->courseType->title ?? '-', // 🔥 ini yang kamu mau
                    'title'       => $course->title,
                    'thumbnail'   => $course->thumbnail,
                    'instructor'  => $course->instructor->name ?? '-',
                    'objective'   => $course->objective,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Course Type', // 🔥 tambahan
            'Title',
            'Thumbnail',
            'Instructor',
            'Objective',
        ];
    }
}