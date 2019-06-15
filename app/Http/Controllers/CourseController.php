<?php

namespace App\Http\Controllers;

use App\Course;
use App\Module;
use App\Student;
use App\Grading;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Pagination\LengthAwarePaginator;

class CourseController extends Controller
{

    /**
     * Display a listing of the courses.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$this->isAuthenticated()) {
            return view('login');
        }
        if (!$this->authorize('mitarbeiter')) {
            abort(403);
        }
        $courses = Course::orderBy('updated_at', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->paginate(5);
        if ($request->ajax()) {
            return view('partials.courseTable', compact('courses'));
        }
        return view('teacher', compact('courses'));
    }

    /**
     * Display the search result of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        if (!$this->isAuthenticated()) {
            return view('login');
        }
        if (!$this->authorize('mitarbeiter')) {
            abort(403);
        }
        if ($request->query('query') == '') {
            $courses = Course::orderBy('updated_at', 'DESC')->paginate(5);
            if ($request->ajax()) {
                return view('partials.courseTable', compact('courses'));
            } else {
                return view('teacher', compact('courses'));
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
        $paginate = 5;
        $page = Input::get('page', 1);
        $offset = ($page * $paginate) - $paginate;
        $options = [
            'path' => $request->url(),
            'query' => $request->query()
        ];
        $courses = $courses->all();
        $itemsForCurrentPage = array_slice($courses, $offset, $paginate, true);
        $courses = new LengthAwarePaginator($itemsForCurrentPage, count($courses), $paginate, $page, $options);

        if ($request->ajax()) {
            return view('partials.courseTable', compact('courses'));
        }
        return view('teacher', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!$this->isAuthenticated()) {
            return view('login');
        }
        if (!$this->authorize('mitarbeiter')) {
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
        if (!$this->isAuthenticated()) {
            return view('login');
        }
        if (!$this->authorize('mitarbeiter')) {
            abort(403);
        }
        $rules = [
            'module_no' => 'required',
            'module_title' => 'required',
            'semester' => 'required'
        ];
        $validatedRequest = $request->validate($rules);

        $module = Module::firstOrCreate([
            'number' => $validatedRequest['module_no'],
            'title' => $validatedRequest['module_title']
        ]);
        $course = new Course;
        $course->module_id = $module->id;
        $course->semester = $validatedRequest['semester'];
        $course->save();

        if ($request->ajax()) {
            return view('partials.courseTableEntry', compact('course'));
        }
        return redirect('/courses/' . $course->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        if (!$this->isAuthenticated()) {
            return view('login');
        }
        if (!$this->authorize('mitarbeiter')) {
            abort(403);
        }
        // decrypt uni_identifiers and grades
        $course->gradings->each(function ($grading) {
            $grading->decryptUniIdentifier();
            $grading->decryptGrade();
        });

        if (request()->ajax()) {
            $grades = $course->gradings; //encrypted?
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
        if (!$this->isAuthenticated()) {
            return view('login');
        }
        if (!$this->authorize('mitarbeiter')) {
            abort(403);
        }
        return view('courses.edit', compact('course'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course)
    {
        if (!$this->isAuthenticated()) {
            return view('login');
        }
        if (!$this->authorize('mitarbeiter')) {
            abort(403);
        }
        $rules = [
            'module_no' => 'required',
            'module_title' => 'required',
            'semester' => 'required'
        ];
        $validatedRequest = $request->validate($rules);

        // refactor?
        if (!$this->isModuleChanged($validatedRequest, $course)) {
            if ($course->semester === $validatedRequest['semester']) {
                if ($request->ajax()) {
                    return response()->json([
                        'error' => 'Daten unverändert'
                    ]);
                } else {
                    session()->flash('error', 'Daten unverändert');
                    return redirect("courses/{$course->id}/edit");
                }
            } else {
                // check if the new course already exists
                $newCourse = Course::where('module_id', $course->module_id)
                            ->where('semester', $validatedRequest['semester'])->first();
                if ($newCourse) {
                    if ($request->ajax()) {
                        return response()->json([
                            'error' => 'Kurs für das angegebene Semester existiert bereits.'
                        ]);
                    } else {
                        session()->flash('error', 'Der Kurs existiert bereits.');
                        return redirect("courses/{$course->id}/edit");
                    }
                } else {
                    $course->semester = $validatedRequest['semester'];
                    $course->save();
                    if ($request->ajax()) {
                        return view('partials.courseTableEntry', compact('course'));
                    } else {
                        session()->flash('change', 'Semester geändert');
                        return redirect("courses/{$course->id}");
                    }
                }
            }
            // check if semester different and if course already exists
        } else {
            // check if new module exists already
            $module = Module::where('number', $validatedRequest['module_no'])
                        ->where('title', $validatedRequest['module_title'])->first();
            if (!$module) {
                $module = Module::create([
                    'number' => $validatedRequest['module_no'],
                    'title' => $validatedRequest['module_title']
                ]);
                $course->module_id = $module->id;
                $course->semester = $validatedRequest['semester'];
                $course->load('module')->save();
                if ($request->ajax()) {
                    return view('partials.courseTableEntry', compact('course'));
                } else {
                    session()->flash('change', 'Änderung erfolgreich');
                    return redirect("courses/{$course->id}");
                }
            } else {
                // check if a course based on the new module already exists for the given semester
                $newCourse = Course::where('module_id', $module->id)
                                ->where('semester', $validatedRequest['semester'])->first();
                if ($newCourse) {
                    if ($request->ajax()) {
                        return response()->json([
                            'error' => 'Kurs für das angegebene Semester existiert bereits.'
                        ]);
                    } else {
                        session()->flash('error', 'Der Kurs existiert bereits.');
                        return redirect("courses/{$course->id}/edit");
                    }
                } else {
                    $course->module_id = $module->id;
                    $course->semester = $validatedRequest['semester'];
                    $course->load('module')->save();
                    if ($request->ajax()) {
                        return view('partials.courseTableEntry', compact('course'));
                    } else {
                        session()->flash('change', 'Änderung erfolgreich');
                        return redirect("courses/{$course->id}");
                    }
                }
            }
        }
    }

    /**
     * Remove the specified grading from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Course $course)
    {
        if (!$this->isAuthenticated()) {
            return view('login');
        }
        if (!$this->authorize('mitarbeiter')) {
            abort(403);
        }
        $course->delete();
        if ($request->ajax()) {
            return $course;
        }
        session()->flash('delete', 'Lehrveranstaltung gelöscht.');
        return redirect('/courses');
    }

    /**
     * Check if the module in the validatedRequest matches the module in $course.
     *
     * @param  Array $validatedRequest
     * @param  \App\Course  $course
     * @return Boolean
     */
    private function isModuleChanged($validatedRequest, Course $course)
    {
        $oldModuleNo = $course->module->number;
        $oldModuleTitle = $course->module->title;
        if ($oldModuleTitle != $validatedRequest['module_title'] ||
            $oldModuleNo != $validatedRequest['module_no']
            ) {
            return true;
        }
        return false;
    }
}
