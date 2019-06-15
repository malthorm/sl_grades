<?php

namespace App\Http\Controllers;

use App\Course;
use App\Student;
use App\Grading;
use Illuminate\Http\Request;

class GradingController extends Controller
{
    /**
     * Display a listing of the grades for the authenticated/specified student.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$this->isAuthenticated()) {
            return view('login');
        }
        if (!($this->authorize('student') || $this->authorize('mitarbeiter'))) {
            abort(403);
        }
        // authorization validation
        if (!$request->filled('uni_identifier')) {
            return view('student');
        }
        //decryption needed
        $student = Student::findByUniIdentifier($request->input('uni_identifier'));

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
        if (!$this->isAuthenticated()) {
            return view('login');
        }
        if (!$this->authorize('mitarbeiter')) {
            abort(403);
        }
        $attributes = $request->validate([
            'uni_identifier' => 'required|min:2',
            'grade' => 'required|regex:/^[1-5].[037]$/'
        ]);

        // first check if already graded, so you don't have to decrypt all students
        if ($course->isGraded($attributes['uni_identifier'])) {
            if ($request->ajax()) {
                return response()->json([
                    'studentGraded' => true,
                    'uni_identifier' => $attributes['uni_identifier']
                ]);
            } else {
                session()->flash('danger', $attributes['uni_identifier'] . ' wurde bereits benotet');
                return redirect("/courses/$course->id");
            }
        }

        $student = Student::findByUniIdentifier($attributes['uni_identifier']);
        if (!$student) {
            $student = Student::create([
                'uni_identifier' => encrypt($attributes['uni_identifier'])
            ]);
        }

        $grade = $course->gradeStudent($student, $attributes['grade']);

        if ($request->ajax()) {
            return response()->json([
                'id' => $grade->id,
                'uni_identifier' => $attributes['uni_identifier'],
                'grade' => $attributes['grade']
            ]);
        } else {
            session()->flash('message', $attributes['uni_identifier'] . ' benotet');
            return redirect("/courses/$course->id");
        }
    }


    public function destroy(Grading $grading)
    {
        if (!$this->isAuthenticated()) {
            return view('login');
        }
        if (!$this->authorize('mitarbeiter')) {
            abort(403);
        }
        if (request()->ajax()) {
            Grading::destroy($grading->id);
            return response()->json([
                'success' => true,
                'id' => $grading->decryptUniIdentifier(true)
            ]);
        }
        $grading->delete();
        session()->flash('message', $grading->decryptUniIdentifier(true) .  ' gelöscht');

        return redirect("/courses/$grading->course_id") ;
    }
}
