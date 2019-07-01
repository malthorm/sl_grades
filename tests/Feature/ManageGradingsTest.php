<?php

namespace Tests\Feature;

use App\Grading;
use Tests\TestCase;
use Illuminate\Support\Facades\File;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageGradingsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_admin_can_create_new_gradings()
    {
        $this->signInAdmin();

        $student = factory('App\Student')->create([
            'uni_identifier' => encrypt('testAdmin')
        ]);
        $course = factory('App\Course')->create();

        $attributes = [
            'uni_identifier' => 'testAdmin',
            'grade' => '2.0'
        ];

        $this->post("grades/" . $course->id, $attributes)
             ->assertSessionHas('message', 'testAdmin benotet')
             ->assertRedirect($course->path());

        $attributes = [
            'student_id' => $student->id,
            'course_id' => $course->id
        ];

        $grading = Grading::where($attributes)->first();
        $grading->decryptGrade();
        $this->assertEquals('2.0', $grading->grade);
    }

    /** @test */
    public function a_student_cannot_be_graded_twice_in_the_same_course()
    {
        $this->withoutExceptionHandling();
        $this->signInAdmin();

        $student = factory('App\Student')->create([
            'uni_identifier' => encrypt('testAdmin')
        ]);
        $course = factory('App\Course')->create();

        $attributes = [
            'uni_identifier' => 'testAdmin',
            'grade' => '2.0'
        ];

        $this->post("grades/" . $course->id, $attributes);

        $this->post("grades/" . $course->id, $attributes)
        ->assertSessionHas('danger', 'testAdmin wurde bereits benotet')
        ->assertRedirect($course->path());

        $this->assertCount(1, Grading::all());
    }

    /** @test */
    public function an_admin_can_destroy_any_grading()
    {
        $this->signInAdmin();

        $grading = factory('App\Grading')->create();

        $this->delete($grading->path());

        $this->assertDatabaseMissing('gradings', $grading->toArray());
    }

    /** @test */
    public function a_student_cannot_create_new_gradings()
    {
        $this->signInStudent();

        $student = factory('App\Student')->create();
        $course = factory('App\Course')->create();

        $this->post("grades/" . $course->id, [
            'student_id' => $student->id,
            'course_id' => $course->id,
            'grade' => '2.0'
        ])->assertStatus(403);
    }

    /** @test */
    public function a_student_can_delete_his_own_gradings_but_not_others()
    {
        $this->signInStudent();

        $grading = factory('App\Grading')->create();
        $student = factory('App\Student')->create([
            'uni_identifier' => encrypt('testStudent')
        ]);
        $gradingOwned = factory('App\Grading')->create([
            'student_id' => $student->id
        ]);
        $this->delete($gradingOwned->path());
        $this->assertDatabaseMissing('gradings', $gradingOwned->toArray());

        $this->delete($grading->path())->assertStatus(403);
    }

    /** @test */
    public function a_student_can_view_their_own_gradings_but_not_the_gradings_of_others()
    {
        $this->withoutExceptionHandling();
        $this->signInStudent();

        $studentLoggedIn = factory('App\Student')->create([
            'uni_identifier' => encrypt('testStudent')
        ]);
        $studentTwo = factory('App\Student')->create();
        $course = factory('App\Course')->create();

        $course->gradeStudent($studentLoggedIn, '2.0');
        $course->gradeStudent($studentTwo, '1.0');
        // dd($course);

        $this->get('grades')
            ->assertSee($course->module->number)
            ->assertSee($course->module->title)
            ->assertSee('2.0')
            ->assertDontSee('1.0');
    }

    // FAILED TO OPEN STREAM - PROTOCOL ERROR
    /** @test */
    // public function an_admin_can_grade_students_with_csv_file()
    // {
    //     $this->signInAdmin();

    //     $course = factory('App\Course')->create();
    //     $file = File::get(base_path('tests/testFile/grades.csv'));

    //     $response = $this->post('grades/'. $course->id .'/csv', [
    //         'file' => file($file)
    //     ]);
    // }
}
