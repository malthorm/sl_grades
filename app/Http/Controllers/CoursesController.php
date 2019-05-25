<?php

namespace App\Http\Controllers;

use App\Course;
use App\Module;
use Illuminate\Http\Request;

class CoursesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $courses = Course::all();
        return view('teacher', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('courses.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // TODO:: validationRule überprüfen
    public function store(Request $request)
    {
        $validatedRequest = request()->validate([
            'module_nr' => 'required',
            'module_name' => 'required',
            'semester' => 'required'
        ]);

        $module = Module::firstOrCreate([
            'module_nr' => $validatedRequest['module_nr'],
            'name' => $validatedRequest['module_name']
        ]);

        $attributes = [
            'module_id' => $module->id,
            'semester' => $validatedRequest['semester']
        ];

        if (Course::where('module_id', '=', $module->id)
                    ->where('semester', '=', $attributes['semester'])
                    ->get()->isNotEmpty()) {
            // TODO flash error course exists already
            dd('test');
            return redirect('/courses');
        }
        Course::create($attributes);
        session()->flash('message', 'Lehrveranstaltung erfolgreich erstellt.');

        return redirect('/courses');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        $course->delete();
        session()->flash('message', 'Lehrveranstaltung gelöscht.');
        return redirect('/courses');
    }
}
