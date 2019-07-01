<?php

namespace App\Http\Controllers;

use App\Course;
use App\Grading;
use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\GradeHandlingException;
use App\Exceptions\UniIdentifierException;
use App\Exceptions\InvalidStudentException;

class GradingController extends Controller
{
    /**
     * Display a listing of the grades for the authenticated/specified student.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorizeRequest('student');
        try {
            // if (env('APP_ENV') == 'production') {
            $user = $_SERVER['REMOTE_USER'];
            $student = Student::findByUniIdentifier($user);
            return view('student', compact('student'));

            // for testing
            // } else {
            //     if (!request()->filled('uni_identifier')) {
            //         return view('student_debug');
            //     }
            //     $student = Student::findByUniIdentifier(
            //         request()->input('uni_identifier')
            //     );
            //     return view('student_debug', compact('student'));
            // }
        } catch (InvalidStudentException $e) {
            report($e);
            return back()->withError(
                'Es scheint ein Problem mit dem Unikennzeichen zu geben.' .
                ' Kontaktieren Sie den Administrator.'
            )->withInput();
        }
    }


    /**
     * Store a newly created grading in storage.
     *
     * @param  \App\Course $course
     * @return \Illuminate\Http\Response
     * @throws App\Exceptions\UniIdentifierException
     * @throws App\Exceptions\InvalidStudentException
     */
    public function store(Course $course)
    {
        $this->authorizeRequest('admin');

        $attributes = $this->validateRequest();

        try {
            // first check if already graded in the course, so you don't have to decrypt all students
            if ($course->isGraded($attributes['uni_identifier'])) {
                if (request()->ajax()) {
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
                    return redirect($course->path());
                }
            }

            $student = Student::findOrCreate($attributes['uni_identifier']);
            $grade = $course->gradeStudent($student, $attributes['grade']);
        } catch (UniIdentifierException $e) {
            if (request()->ajax()) {
                return response()->json([
                    'msg' => $e->getMessage(),
                    'exception' => true
                ]);
            }
            return back()->withError($msg)->withInput();
        } catch (InvalidStudentException $e) {
            report($e);
            $msg = 'Fehler in der Datenbank. Kontaktieren Sie den Administrator.';
            if (request()->ajax()) {
                return response()->json([
                    'msg' => $msg,
                    'exception' => true
                ]);
            }
            return back()->withError($msg)->withInput();
        }

        if (request()->ajax()) {
            return response()->json([
                    'id' => $grade->id,
                    'uni_identifier' => $attributes['uni_identifier'],
                    'grade' => $attributes['grade']
                ]);
        } else {
            session()->flash('message', $attributes['uni_identifier'] . ' benotet');
            return redirect($course->path());
        }
    }

    /**
     * Remove the specified grading from storage.
     *
     * @param  \App\Grading $grading
     * @return \Illuminate\Http\Response
     * @throws App\Exceptions\InvalidStudentException
     */
    public function destroy(Grading $grading)
    {
        $this->authorizeRequest('student');

        try {
            $uni_identifier = $grading->decryptUniIdentifier(true);
        } catch (InvalidStudentException $e) {
            report($e);
            $msg = 'Fehler in der Datenbank. Kontaktieren Sie den' .
                ' Administrator.';
            if (request()->ajax()) {
                return response()->json([
                    'msg' => $msg,
                    'exception' => true
                ]);
            }
            return back()->withErrors($msg)->withInput();
        }
        // students are only allowed to delete there own gradings
        if ($this->authorization() !== 'admin') {
            if ($_SERVER['REMOTE_USER'] != $uni_identifier) {
                abort(403);
            }
        }
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
        return redirect()->back();
    }

    /**
     * Imports gradings for a course from a csv file.
     *
     * @param  \App\Course $course
     *
     * @return \Illuminate\Http\Response
     * @throws App\Exceptions\UniIdentifierException
     * @throws \Exception
     */
    public function csvImport(Course $course)
    {
        $this->authorizeRequest('admin');

        $validator = Validator::make(request()->all(), [
            'file' => 'required'
        ]);

        if ($validator->fails()) {
            if (request()->ajax()) {
                return response()->json([
                    'exception' => true,
                    'msg' => 'Datei nicht gefunden.'
                ]);
            }
            return redirect()->back()->withErrors($validator);
        }
        $file = request()->file('file');
        if ($file->getClientOriginalExtension() !== "csv" &&
            $file->getMimeType() !== "text/csv") {
            if (request()->ajax()) {
                return response()->json([
                    'errors' => 'Keine .csv Datei.'
                ]);
            }
            return redirect()->back()->withErrors('Keine .csv Datei.');
        }
        $csvData = rtrim(file_get_contents($file));
        $rows = array_map('str_getcsv', explode("\n", $csvData));
        $header = array('uni_identifier', 'grade');

        // if the second entry in the first row does not look like a grade,
        // the frist row will be interpreted as a header and discarded
        if (!preg_match("/^[\d][.,][\d]$/", $rows[0][1])) {
            array_shift($rows);
        }
        // collect failed an successful gradings for response
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

            if (!preg_match("/^[1-3][.][037]$|^[45][.]0$/", $grade)) {
                $errors[] = $uni_identifier . ',' . $grade . ' - Note ungültig';
                continue;
            }

            // check that same student not graded twice in the csv
            $csvDuplicate = false;
            foreach ($gradings as $grading) {
                if ($grading['uni_identifier'] === $uni_identifier) {
                    $errors[] = $uni_identifier . ' mehrfach in csv-Datei.';
                    $csvDuplicate = true;
                }
            }
            if ($csvDuplicate) {
                continue;
            }

            if (!$course->isGraded($uni_identifier)) {
                try {
                    $student = Student::findOrCreate($uni_identifier);
                } catch (UniIdentifierException $e) {
                    $errors[] = $uni_identifier . ' - ' . $e->getMessage();
                    continue;
                }
                $grading = $course->gradeStudent($student, $grade);
                $gradings[] = [
                    'id' => $grading->id,
                    'uni_identifier' => $row['uni_identifier'],
                    'grade' => $grade
                ];
            } else {
                $errors[] = $uni_identifier . ' ist bereits benotet.';
            }
        }
        if (request()->ajax()) {
            return response()->json([
                'errors' => $errors,
                'gradings' => $gradings
            ]);
        } else {
            return redirect($course->path())->withErrors($errors);
        }
    }

    /**
     * Don't keep students in storage if they don't have any gradings.
     *
     * @param \App\Student $student
     */
    private function maintenance(Student $student)
    {
        if ($student->grades->isEmpty()) {
            $student->delete();
        }
    }

    /**
     * Validates a form post request for gradings.
     * @return array Returns either the validated attributes or the validation errors.
     */
    protected function validateRequest()
    {
        $validationErrorMsg = [
            'uni_identifier.required' => 'Bitte ein Unikennzeichen angeben.',
            'grade.required' => 'Bitte eine Note eintragen',
            'grade.regex' => 'Keine gültige Note.'
        ];

        return request()->validate([
            'uni_identifier' => 'required',
            'grade' => ['required', 'regex:/^[1-3][.][037]$|^[45][.]0$/']
        ], $validationErrorMsg);
    }
}
