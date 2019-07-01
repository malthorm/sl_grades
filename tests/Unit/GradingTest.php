<?php

namespace Tests\Unit;

use App\Course;
use App\Grading;
use App\Student;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GradingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_path()
    {
        $grading = factory('App\Grading')->create();

        $this->assertEquals('grades/' . $grading->id, $grading->path());
    }

    /** @test */
    public function it_belongs_to_a_courses()
    {
        $grading = factory('App\Grading')->create();

        $this->assertInstanceOf(Course::class, $grading->course);
    }

    /** @test */
    public function it_belongs_to_a_student()
    {
        $grading = factory('App\Grading')->create();

        $this->assertInstanceOf(Student::class, $grading->student);
    }

    /** @test */
    public function it_can_decrypt_its_own_grade()
    {
        $grade = '2.0';

        $grading = factory('App\Grading')->create([
            'grade' => encrypt($grade)
        ]);

        $grading->decryptGrade();

        $this->assertEquals($grading->grade, $grade);
    }
}
