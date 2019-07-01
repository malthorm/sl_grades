<?php

namespace Tests\Feature;

use App\Course;
use App\Module;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageCoursesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function students_or_guests_cannot_manage_courses()
    {
        $this->signInStudent();

        $module = factory('App\Module')->create();
        $course = factory('App\Course')->create([
            'module_id' => $module->id
        ]);
        $postRequest = array([
            'module_no' => $module->number,
            'module_title' => $module->title,
            'semester' => $course->semester
        ]);

        $this->post('courses', $postRequest)->assertStatus(403);
        $this->delete($course->path(), $course->toArray())->assertStatus(403);
        $this->get('courses/create')->assertStatus(403);
        $this->get($course->path() .'/edit')->assertStatus(403);
        $this->get('courses')->assertStatus(403);
        $this->get($course->path())->assertStatus(403);
        $this->get('courses/search')->assertStatus(403);
    }

    /** @test */
    public function an_admin_can_create_a_course()
    {
        $this->signInAdmin();

        $attributes = array(
            'module_no' => '123456',
            'module_title' => 'Title',
            'semester' => 'WS 18'
        );

        $response = $this->post('courses', $attributes);
        $module = Module::where([
            'number' => $attributes['module_no'],
            'title' => $attributes['module_title']
        ])->first();
        $course = Course::where([
            'module_id' => $module->id,
            'semester' => $attributes['semester']
        ])->first();

        $response->assertRedirect($course->path());

        $this->get($course->path())
            ->assertSee($attributes['module_no'])
            ->assertSee($attributes['module_title'])
            ->assertSee($attributes['semester']);
    }

    /** @test */
    public function an_admin_cannot_create_the_same_course_twice()
    {
        $this->signInAdmin();

        $attributes = array(
            'module_no' => '123456',
            'module_title' => 'Title',
            'semester' => 'WS 18'
        );

        $response = $this->post('courses', $attributes);

        $response = $this->post('courses', $attributes)
            ->assertSessionHas('message', 'Kurs existiert bereits.');
    }

    /** @test */
    public function an_admin_can_view_a_course()
    {
        $this->signInAdmin();

        $course = factory('App\Course')->create();

        $this->get($course->path())
             ->assertSee($course->semester);
    }

    /** @test */
    public function an_admin_can_view_the_decrypted_gradings_of_a_course()
    {
        $this->withoutExceptionHandling();
        $this->signInAdmin();

        $course = factory('App\Course')->create();

        $student = factory('App\Student')->create([
            'uni_identifier' => encrypt('test1')
        ]);
        $studentTwo = factory('App\Student')->create([
            'uni_identifier' => encrypt('test2')
        ]);

        $course->gradeStudent($student, '1.0');
        $course->gradeStudent($studentTwo, '2.3');

        $this->get($course->path())
             ->assertSee('test1')
             ->assertSee('1.0')
             ->assertSee('test2')
             ->assertSee('2.3');
    }

    /** @test */
    public function a_course_requires_a_module_number()
    {
        $this->signInAdmin();

        $this->post('courses', [
            'module_no' => '',
            'module_title' => 'Datenbanken',
            'semester' => 'WS 18'
        ])->assertSessionHasErrors('module_no');
    }

    /** @test */
    public function a_course_requires_a_module_title()
    {
        $this->signInAdmin();

        $this->post('courses', [
            'module_no' => '123456',
            'module_title' => '',
            'semester' => 'WS 18'
        ])->assertSessionHasErrors('module_title');
    }

    /** @test */
    public function a_course_requires_a_semester()
    {
        $this->signInAdmin();

        $this->post('courses', [
            'module_no' => '123456',
            'module_title' => 'Datenbanken',
            'semester' => ''
        ])->assertSessionHasErrors('semester');
    }

    /** @test */
    public function an_admin_can_delete_a_course()
    {
        $this->signInAdmin();

        $course = factory('App\Course')->create();

        $this->delete($course->path())
             ->assertRedirect('courses')
             ->assertSessionHas('delete', 'Lehrveranstaltung gelöscht.');

        $this->assertDatabaseMissing('courses', $course->toArray());
    }

    /** @test */
    public function an_admin_can_update_a_course_with_a_new_module()
    {
        $this->signInAdmin();

        $course = factory('App\Course')->create();

        $newModule = factory('App\Module')->create();

        $this->patch($course->path(), [
            'module_no' => $newModule->number,
            'module_title' => $newModule->title,
            'semester' => $course->semester
        ])
        ->assertRedirect($course->path());

        $this->get($course->path() . '/edit')->assertOk();

        $this->assertDatabaseHas('courses', [
            'module_id' => $newModule->id,
            'semester' => $course->semester
        ]);
    }

    /** @test */
    public function an_admin_can_update_a_course_with_a_new_semester()
    {
        $this->signInAdmin();

        $course = factory('App\Course')->create();

        $newSemester = 'WS 18/19';


        $this->patch($course->path(), [
            'module_no' => $course->module->number,
            'module_title' => $course->module->title,
            'semester' => $newSemester
        ])
        ->assertRedirect($course->path())
        ->assertSessionHas('change', 'Semester geändert');

        $this->get($course->path() . '/edit')->assertOk();

        $this->assertDatabaseHas('courses', [
            'module_id' => $course->module_id,
            'semester' => $newSemester
        ]);
    }

    /** @test */
    public function an_admin_cannot_update_a_course_with_the_same_values()
    {
        $this->signInAdmin();

        $course = factory('App\Course')->create();

        $this->patch($course->path(), [
            'module_no' => $course->module->number,
            'module_title' => $course->module->title,
            'semester' => $course->semester
        ])
        ->assertSessionHas('error', 'Daten unverändert');
    }

    /** @test */
    public function an_admin_cannot_updated_a_course_with_new_semester_if_that_would_cause_duplication()
    {
        $this->signInAdmin();

        $module = factory('App\Module')->create();

        $courseOne = factory('App\Course')->create([
            'module_id' => $module->id,
            'semester' => 'SS 19'
        ]);

        $courseTwo = factory('App\Course')->create([
            'module_id' => $module->id,
            'semester' => 'SS 20'
        ]);

        $this->patch($courseOne->path(), [
            'module_no' => $courseOne->module->number,
            'module_title' => $courseOne->module->title,
            'semester' => $courseTwo->semester
        ])
        ->assertSessionHas('error', 'Der Kurs existiert bereits für das Semester.')
        ->assertRedirect($courseOne->path() . '/edit');
    }

    /** @test */
    public function an_admin_cannot_updated_a_course_with_new_module_and_old_semester_if_that_would_cause_duplication()
    {
        $this->signInAdmin();

        $course = factory('App\Course')->create([
            'semester' => 'SS 19'
        ]);
        $module = factory('App\Module')->create();

        $courseTwo = factory('App\Course')->create([
            'module_id' => $module->id,
            'semester' => 'SS 19'
        ]);

        $this->patch($course->path(), [
            'module_no' => $module->number,
            'module_title' => $module->title,
            'semester' => $course->semester
        ])
        ->assertSessionHas('error', 'Der Kurs existiert bereits.')
        ->assertRedirect($course->path() . '/edit');
    }

    /** @test */
    public function an_admin_cannot_updated_a_course_with_all_new_attributes_if_that_would_cause_duplication()
    {
        $this->signInAdmin();

        $course = factory('App\Course')->create([
            'semester' => 'SS 19'
        ]);
        $module = factory('App\Module')->create();

        $courseTwo = factory('App\Course')->create([
            'module_id' => $module->id,
            'semester' => 'SS 20'
        ]);

        $this->patch($course->path(), [
            'module_no' => $module->number,
            'module_title' => $module->title,
            'semester' => 'SS 20'
        ])
        ->assertSessionHas('error', 'Der Kurs existiert bereits.')
        ->assertRedirect($course->path() . '/edit');
    }
}
