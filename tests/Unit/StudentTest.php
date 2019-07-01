<?php

namespace Tests\Unit;

use App\Student;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StudentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_student_has_grades()
    {
        $student = factory('App\Student')->create();

        $this->assertInstanceOf(Collection::class, $student->grades);
    }

    /** @test */
    public function it_can_decrypt_its_own_uni_identifier()
    {
        $uni_identifier = 'test';
        $student = factory('App\Student')->create([
            'uni_identifier' => encrypt($uni_identifier)
        ]);

        $this->assertEquals($student->getPlainUniIdentifier(), $uni_identifier);
    }

    /** @test */
    public function it_can_be_found_in_db_by_encrypted_uni_identifier()
    {
        $uni_identifier = 'test';
        $student = factory('App\Student')->create([
            'uni_identifier' => encrypt($uni_identifier)
        ]);

        $dbStudent = Student::findByUniIdentifier($uni_identifier);
        $this->assertEquals($student->id, $dbStudent->id);
    }

    /** @test */
    public function it_creates_only_if_not_already_in_db()
    {
        $uni_identifier = 'test';
        $student = Student::findOrCreate($uni_identifier);

        $dbStudents = Student::get();
        $this->assertCount(1, $dbStudents);
        $this->assertEquals($student->id, $dbStudents[0]->id);

        $student = Student::findOrCreate($uni_identifier);
        $this->assertCount(1, $dbStudents);
    }

    /** @test */
    public function it_can_detect_the_latest_attempt_made_in_a_module()
    {
        $student = factory('App\Student')->create();
        $module = factory('App\Module')->create([
            'id' => 1
        ]);

        $courses = factory('App\Course', 2)->create([
            'module_id' => 1
        ]);

        $gradeOne = $courses[0]->gradeStudent($student, '5.0');
        $gradeTwo = $courses[0]->gradeStudent($student, '5.0');

        $first = $student->isLatestAttemptInModule($module, $gradeOne);
        $latest = $student->isLatestAttemptInModule($module, $gradeTwo);

        $this->assertFalse($first);
        $this->assertTrue($latest);
    }


    /** @test */
    public function it_can_count_how_often_the_student_has_received_a_grade_in_a_module()
    {
        $student = factory('App\Student')->create();
        $module = factory('App\Module')->create();

        $courses = factory('App\Course', 4)->create([
            'module_id' => $module->id
        ]);

        foreach ($courses as $course) {
            factory('App\Grading')->create([
                'student_id' => $student->id,
                'course_id' => $course->id
            ]);
        }

        $count = $student->countAttemptsInModule($module);

        $this->assertSame($count, 4);
    }
}
