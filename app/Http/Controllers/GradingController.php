<?php

namespace App\Http\Controllers;

use App\Course;
use App\Grading;
use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\GradeHandlingException;
use App\Exceptions\InvalidStudentException;

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
        try {
            if (!$request->filled('uni_identifier')) {
                return view('student');
            }
            $student = Student::findByUniIdentifier(
                $request->input('uni_identifier')
            );
            abort_unless($student, 404);
            return view('student', compact('student'));
        } catch (GradeHandlingException $e) {
            report($e);
            return back()->withError(
                'Ein unerwarteter Fehler ist aufgetreten.'
            )->withInput();
        }
    }

    /**
     * Store a newly created grading in storage.
     *
     * @param  \App\Course $course
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Course $course, Request $request)
    {
        // if (!$this->isAuthenticated()) {
        //     return view('login');
        // }
        // if (!$this->authorize('mitarbeiter')) {
        //     abort(403);
        // }
        $attributes = $request->validate([
            'uni_identifier' => 'required|min:2',
            'grade' => 'required|regex:/^[1-5][.,][037]$/'
        ]);
        try {
            // first check if already graded, so you don't have to decrypt all students
            if ($course->isGraded($attributes['uni_identifier'])) {
                if ($request->ajax()) {
                    return response()->json([
                        'studentGraded' => true,
                        'uni_identifier' => $attributes['uni_identifier']
                    ]);
                } else {
                    session()->flash(
                        'danger',
                        $attributes['uni_identifier'] .
                        ' wurde bereits benotet'
                    );
                    return redirect("courses/$course->id");
                }
            }

            $student = Student::findOrCreate($attributes['uni_identifier']);
            $grade = $course->gradeStudent($student, $attributes['grade']);

            if ($request->ajax()) {
                return response()->json([
                    'id' => $grade->id,
                    'uni_identifier' => $attributes['uni_identifier'],
                    'grade' => $attributes['grade']
                ]);
            } else {
                session()->flash('message', $attributes['uni_identifier'] . ' benotet');
                return redirect("courses/$course->id");
            }
        } catch (GradeHandlingException $e) {
            report($e);
            $msg = 'Ein unerwarteter Fehler ist aufgetreten. Note konnte' .
            ' nicht gespeichert werden.';
            if ($request->ajax()) {
                return response()->json([
                    'msg' => $msg,
                    'exception' => true
                ]);
            }
            return back()->withError($msg)->withInput();
        }
    }

    /**
     * Remove the specified grading from storage.
     *
     * @param  \App\Grading $grading
     * @return \Illuminate\Http\Response
     */
    public function destroy(Grading $grading)
    {
        if (!$this->isAuthenticated()) {
            return view('login');
        }
        if (!$this->authorize('mitarbeiter')) {
            abort(403);
        }
        try {
            $uni_identifier = $grading->decryptUniIdentifier(true);
            if (request()->ajax()) {
                Grading::destroy($grading->id);
                $this->maintenance($grading->student);
                return response()->json([
                    'success' => true,
                    'id' => $uni_identifier
                ]);
            }

            $grading->delete();
            $this->maintenance($grading->student);
            session()->flash(
                'message',
                $uni_identifier . ' gelöscht'
            );
            return redirect("courses/$grading->course_id");
        } catch (GradeHandlingException $e) {
            report($e);
            $msg = 'Ein unerwarteter Fehler ist aufgetreten. Note konnte' .
            ' nicht gelöscht werden.';
            if ($request->ajax()) {
                return response()->json([
                    'msg' => $msg,
                    'exception' => true
                ]);
            }
            return back()->withError($msg)->withInput();
        } catch (InvalidStudentException $e) {
            report($e);
            $msg = 'Ein unerwarteter Fehler ist aufgetreten. Student' .
            ' nicht entschlüsselt werden werden.';
            if ($request->ajax()) {
                return response()->json([
                    'msg' => $msg,
                    'exception' => true
                ]);
            }
            return back()->withError($msg)->withInput();
        }
    }

    /**
     * Imports gradings for a course from a csv file.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Course $course
     *
     * @return \Illuminate\Http\Response
     */
    public function csvImport(Request $request, Course $course)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required'
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'exception' => true,
                    'msg' => 'Datei nicht gefunden.'
                ]);
            }
            return redirect()->back()->withErrors($validator);
        }
        $file = $request->file('file');
        if ($file->getClientOriginalExtension() !== "csv" &&
            $file->getMimeType() !== "text/csv") {
            if ($request->ajax()) {
                return response()->json([
                    'errors' => 'Keine .csv Datei.'
                ]);
            }
            return redirect()->back()->withErrors('Keine .csv Datei.');
        }

        try {
            $csvData = rtrim(file_get_contents($file));
            $rows = array_map('str_getcsv', explode("\n", $csvData));
            $header = array('uni_identifier', 'grade');

            // if the second entry in the first row is not a valid grade, the frist
            // row will be interpreted as a header and discarded
            if (!preg_match("/^[1-5][.][037]$/", $rows[0][1])) {
                array_shift($rows);
            }

            $errors = array();
            $gradings = array();
            foreach ($rows as $row) {
                try {
                    $row = array_combine($header, $row);
                } catch (\Exception $e) {
                    $errors[] = $row;
                    continue;
                }
                $uni_identifier = trim(e($row['uni_identifier']));
                $grade = trim(e($row['grade']));

                if (!preg_match("/^[1-5][.][037]$/", $grade)) {
                    $errors[] = $uni_identifier . ',' . $grade . ' - ungültig';
                    continue;
                }

                if (!$course->isGraded($uni_identifier)) {
                    $student = Student::findOrCreate($uni_identifier);
                    $grading = Grading::create([
                        'student_id' => $student->id,
                        'course_id' => $course->id,
                        'grade' => encrypt($row['grade'])
                    ]);
                    $gradings[] = [
                        'id' => $grading->id,
                        'uni_identifier' => $row['uni_identifier'],
                        'grade' => $row['grade']
                    ];
                } else {
                    $errors[] = $row['uni_identifier'] . ' ist bereits benotet.';
                }
            }
            if ($request->ajax()) {
                return response()->json([
                    'errors' => $errors,
                    'gradings' => $gradings
                ]);
            } else {
                return redirect("courses/$course->id")->withErrors($errors);
            }
        } catch (GradeHandlingException $e) {
            report($e);
            $msg = 'Ein unerwarteter Fehler ist aufgetreten. Csv Datei' .
            ' konnte nicht importiert werden.';
            if ($request->ajax()) {
                return response()->json([
                    'msg' => $msg,
                    'exception' => true
                ]);
            }
            return back()->withError($msg);
        }
    }

    /**
     * Don't keep students in storage if they don't have any gradings.
     *
     * @param \App\Student $student
     */
    private function maintenance(Student $student)
    {
        try {
            if ($student->grades->isEmpty()) {
                $student->delete();
            }
        } catch (GradeHandlingException $e) {
            report($e);
        }
    }
}
