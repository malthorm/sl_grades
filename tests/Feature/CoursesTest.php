<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CoursesTest extends TestCase
{
    use WithFaker, RefreshDatabase;


    /** @test */
    public function a_teacher_can_create_a_course()
    {
        $this->withoutExceptionHandling();

        $attributes = factory('App\Course')->raw();

        $this->post('/courses', $attributes)->assertRedirect('/courses');

        $this->assertDatabaseHas('courses', $attributes);

        $this->get('/courses')->assertSee($attributes['module_nr']);
    }

    // /** @test */
    // public function a_student_can_view_own_courses()
    // {
    //     $course = factory('App\Course')->create();

    //     // $course->module_nr needs changing (maybe extra id???)
    //     $this->get('/projects/' . $course->module_nr)
    //         ->assertSee($course->semester)
    //         ->assertSee($course_name);
    // }

    /** @test */
    public function a_course_requires_a_module_nr()
    {
        $attributes = factory('App\Course')->raw(['module_nr' => '']);
        $this->post('/courses', $attributes)->assertSessionHasErrors('module_nr');
    }

    /** @test */
    public function a_course_requires_a_semester()
    {
        $attributes = factory('App\Course')->raw(['semester' => '']);
        $this->post('/courses', $attributes)->assertSessionHasErrors('semester');
    }

    /** @test */
    public function a_course_requires_a_name()
    {
        $attributes = factory('App\Course')->raw(['name' => '']);
        $this->post('/courses', $attributes)->assertSessionHasErrors('name');
    }

    // /** @test */
    // public function a_student_cannot_create_a_course()
    // {
    // }
}
