<?php

namespace App\Http\Controllers;

use App\Course;
use App\Student;
use App\Enrollment;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of the grades for the authenticated/specified student.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // authorization validation
        if (!$request->filled('studentId')) {
            return view('student');
        }
        $student = Student::find($request->input('studentId'));
        abort_unless($student, 404);
        return view('student', compact('student'));
    }

    /**
     * Store a newly created grading in storage.
     *
     * @param \App\Course $course
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Course $course, Request $request)
    {
        $attributes = $request->validate([
            'id' => 'required|min:2',
            'grade' => 'required|regex:/^[1-5].[037]$/'
        ]);

        if ($request->ajax()) {
            $student = Student::firstOrCreate(['id' => $attributes['id']]);
            if ($course->isGraded($student)) {
                return response()->json([
                    'studentGraded' => true,
                    'id' => $student->id
                ]);
            }
            $grade = $course->gradeStudent($student, $attributes['grade']);
            return $grade;
        }

        $student = Student::firstOrCreate(['id' => $attributes['id']]);
        if ($course->isGraded($student)) {
            session()->flash('danger', $student->id . ' wurde bereits benotet');
            return redirect("/courses/$course->id");
        }
        $grade = $course->gradeStudent($student, $attributes['grade']);
        session()->flash('message', $student->id . ' benotet');
        return redirect("/courses/$course->id");
    }


    public function destroy(Enrollment $enrollment)
    {
        if (request()->ajax()) {
            Enrollment::destroy($enrollment->id);
            return response()->json([
                'success' => true,
                'id' => $enrollment->student_id
            ]);
        }
        $enrollment->delete();
        session()->flash('message', $enrollment->student_id .  ' gelöscht');

        return redirect("/courses/$enrollment->course_id") ;
    }
}
