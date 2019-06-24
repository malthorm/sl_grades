<?php

namespace App\Http\Controllers;

use App\Course;
use App\Module;
use App\Grading;
use App\Student;
use App\ShibbAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Exception\InvalidGradingException;
use App\Exceptions\CourseHandlingException;
use App\Exceptions\InvalidStudentException;
use Illuminate\Pagination\LengthAwarePaginator;

class CourseController extends Controller
{
    protected $validationRules = [
                'module_no' => 'required',
                'module_title' => 'required',
                'semester' => 'required'
            ];

    /**
     * Display a listing of the courses.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!ShibbAuth::isAuthenticated()) {
            return view('login');
        }
        if (!ShibbAuth::authorize('mitarbeiter')) {
            abort(403);
        }
        try {
            $courses = Course::orderBy('updated_at', 'DESC')
                ->orderBy('created_at', 'DESC')
                ->paginate(7);
            if ($request->ajax()) {
                return view('partials.courseTable', compact('courses'));
            }
            return view('courses.index', compact('courses'));
        } catch (CourseHandlingException $e) {
            report($e);
            $msg = 'Ein unerwarteter Fehler ist aufgetreten. Kurse konnten '.
            'nicht gefetched werden';
            return back()->withError($msg);
        }
    }

    /**
     * Display the search result of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        if (!ShibbAuth::isAuthenticated()) {
            return view('login');
        }
        if (!ShibbAuth::authorize('mitarbeiter')) {
            abort(403);
        }
        try {
            if ($request->query('query') == '') {
                $courses = Course::orderBy('updated_at', 'DESC')->paginate(7);
                if ($request->ajax()) {
                    return view('partials.courseTable', compact('courses'));
                } else {
                    return view('courses.index', compact('courses'));
                }
            }
            $query = '%' . $request->query('query') . '%';
            $modules = Module::where('title', 'LIKE', $query)
                        ->orWhere('number', 'LIKE', $query)
                        ->orderBy('updated_at', 'DESC')
                        ->orderBy('created_at', 'DESC')
                        ->get();

            $courses = Course::where('semester', 'LIKE', $query)
                ->orderBy('updated_at', 'DESC')
                ->orderBy('created_at', 'DESC')
                ->get();

            foreach ($modules as $module) {
                $courses = $courses->union($module->courses);
            }

            // paginator
            $paginate = 7;
            $page = Input::get('page', 1);
            $offset = ($page * $paginate) - $paginate;
            $options = [
                'path' => $request->url(),
                'query' => $request->query()
            ];
            $courses = $courses->all();
            $itemsForCurrentPage = array_slice(
                $courses,
                $offset,
                $paginate,
                true
            );
            $courses = new LengthAwarePaginator(
                $itemsForCurrentPage,
                count($courses),
                $paginate,
                $page,
                $options
            );

            if ($request->ajax()) {
                return view('partials.courseTable', compact('courses'));
            }
            return view('courses.index', compact('courses'));
        } catch (CourseHandlingException $e) {
            report($e);
            $msg = 'Bei der Suche ist ein unerwarteter Fehler aufgetreten';
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!ShibbAuth::isAuthenticated()) {
            return view('login');
        }
        if (!ShibbAuth::authorize('mitarbeiter')) {
            abort(403);
        }
        return view('courses.create');
    }

    /**
     * Store a newly created course in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!ShibbAuth::isAuthenticated()) {
            return view('login');
        }
        if (!ShibbAuth::authorize('mitarbeiter')) {
            abort(403);
        }
        try {
            $validatedRequest = $request->validate($this->validationRules);

            $module = Module::firstOrCreate([
                'number' => $validatedRequest['module_no'],
                'title' => $validatedRequest['module_title']
            ]);
            $course = new Course;
            if ($course->duplicate(
                $module->id,
                $validatedRequest['semester']
            )) {
                $msg = 'Kurs existiert bereits.';
                if ($request->ajax()) {
                    return response()->json([
                        'error' => true,
                        'msg' => $msg
                    ]);
                } else {
                    session()->flash('message', $msg);
                    return redirect()->back()->withInput();
                }
            }
            $course->module_id = $module->id;
            $course->semester = $validatedRequest['semester'];

            $course->save();

            if ($request->ajax()) {
                return view('partials.courseTableEntry', compact('course'));
            }
            return redirect('courses/');
        } catch (CourseHandlingException $e) {
            report($e);
            $msg = 'Ein unerwarteter Fehler ist aufgetreten. Kurs konnte' .
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
     * Display the specified resource.
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        if (!ShibbAuth::isAuthenticated()) {
            return view('login');
        }
        if (!ShibbAuth::authorize('mitarbeiter')) {
            abort(403);
        }
        // decrypt uni_identifiers and grades
        try {
            $course->gradings->each(function ($grading) {
                $grading->decryptUniIdentifier();
                $grading->decryptGrade();
            });
        } catch (InvalidGradingException $e) {
            report($e);
            $msg = 'Ein unerwarteter Fehler ist aufgetreten. Note konnten' .
            ' nicht entschlüsselt werden.';
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
            ' nicht entschlüsselt werden.';
            if ($request->ajax()) {
                return response()->json([
                    'msg' => $msg,
                    'exception' => true
                ]);
            }
            return back()->withError($msg)->withInput();
        }

        if (request()->ajax()) {
            $grades = $course->gradings;
            return view('partials.gradeEntry', compact('grades'));
        }
        return view('courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {
        if (!ShibbAuth::isAuthenticated()) {
            return view('login');
        }
        if (!ShibbAuth::authorize('mitarbeiter')) {
            abort(403);
        }
        return view('courses.edit', compact('course'));
    }

    /**
     * Update the specified course in storage, if an indentical course
     * doesn't already exist in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course)
    {
        if (!ShibbAuth::isAuthenticated()) {
            return view('login');
        }
        if (!ShibbAuth::authorize('mitarbeiter')) {
            abort(403);
        }

        try {
            $validatedRequest = $request->validate($this->validationRules);
            $currentModule = $course->module;
            $newModule = Module::
                        where('number', $validatedRequest['module_no'])
                        ->where('title', $validatedRequest['module_title'])
                        ->first();
            $newSemester = $validatedRequest['semester'];

            if (($currentModule == $newModule) &&
                    ($course->semester === $newSemester)) {
                return $this->courseUpdatedResponse(
                    $request,
                    $course,
                    'Daten unverändert',
                    true
                );
            } elseif (($currentModule == $newModule) &&
                    ($course->semester !== $newSemester)) {
                // check if the  course already exists for the new semester
                if ($course->duplicate($currentModule->id, $newSemester)) {
                    return $this->courseUpdatedResponse(
                        $request,
                        $course,
                        'Der Kurs existiert bereits für das Semester.',
                        true
                    );
                } else {
                    $course->semester = $newSemester;
                    $course->save();
                    return $this->courseUpdatedResponse(
                        $request,
                        $course,
                        'Semester geändert'
                    );
                }
            } else {
                // check if new module exists already
                if (!$newModule) {
                    // create a new Module and update the course
                    $newModule = Module::create([
                            'number' => $validatedRequest['module_no'],
                            'title' => $validatedRequest['module_title']
                        ]);
                    $course->updateAttributes($newModule, $newSemester);
                    return $this->courseUpdatedResponse(
                        $request,
                        $course,
                        'Änderung gespeichert.'
                    );
                } else {
                    // newModule exists already, so check if a course for that
                    // semester exists as well
                    if ($course->duplicate($newModule->id, $newSemester)) {
                        return $this->courseUpdatedResponse(
                            $request,
                            $course,
                            'Der Kurs existiert bereits.',
                            true
                        );
                    } else {
                        $course->updateAttributes($newModule, $newSemester);
                        return $this->courseUpdatedResponse(
                            $request,
                            $course,
                            'Änderung gespeichert.'
                        );
                    }
                }
            }
        } catch (CourseHandlingException $e) {
            report($e);
            $msg = 'Ein unerwarteter Fehler ist aufgetreten. Kurs konnte' .
            ' nicht updated werden.';
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
     * Remove the specified course from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Course $course)
    {
        if (!ShibbAuth::isAuthenticated()) {
            return view('login');
        }
        if (!ShibbAuth::authorize('mitarbeiter')) {
            abort(403);
        }
        try {
            $course->delete();
        } catch (CourseHandlingException $e) {
            report($e);
            $msg = 'Ein unerwarteter Fehler ist aufgetreten. Kurs konnte' .
            ' nicht gelöscht werden.';
            if ($request->ajax()) {
                return response()->json([
                    'msg' => $msg,
                    'exception' => true
                ]);
            }
            return back()->withError($msg);
        }
        $this->maintenance($course->module);

        if ($request->ajax()) {
            return $course;
        }
        session()->flash('delete', 'Lehrveranstaltung gelöscht.');
        return redirect('courses');
    }


    /**
     * Don't keep modules in storage if there aren't any courses based on them.
     *
     * @param \App\Module $module
     */
    private function maintenance(Module $module)
    {
        if ($module->courses->isEmpty()) {
            $module->delete();
        }
    }

    /**
     * Generates a response for the different scenarios in the update method.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Course $course
     * @param string $msg
     * @param bool $error
     * @return \Illuminate\Http\Response
     */
    private function courseUpdatedResponse(
        Request $request,
        Course $course,
        string $msg,
        bool $error = false
    ) {
        if ($error) {
            if ($request->ajax()) {
                return response()->json([
                    'errors' => $msg,
                    'error' => true
                ]);
            } else {
                session()->flash(
                    'error',
                    $msg
                );
                return redirect("courses/{$course->id}/edit");
            }
        }

        if ($request->ajax()) {
            return view(
                'partials.courseTableEntry',
                compact('course')
            );
        } else {
            session()->flash('change', $msg);
            return redirect("courses/{$course->id}");
        }
    }
}
