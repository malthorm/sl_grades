<?php

namespace Tests\Unit;

use App\Course;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CourseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_path()
    {
        $course = factory('App\Course')->create();

        $this->assertEquals('courses/' . $course->id, $course->path());
    }

    /** @test */
    public function it_has_gradings()
    {
        $course = factory('App\Course')->create();
        $this->assertInstanceOf(Collection::class, $course->gradings);
    }

    /** @test */
    public function it_belongs_to_a_module()
    {
        $course = factory('App\Course')->create();
        $this->assertInstanceOf('App\Module'::class, $course->module);
    }

    /** @test */
    public function it_can_assign_grades_to_students()
    {
        $course = factory('App\Course')->create();
        $student = factory('App\Student')->create();

        $grading = $course->gradeStudent($student, '2.0');

        $this->assertCount(1, $course->gradings);
        $this->assertEquals($course->gradings->first()->id, $grading->id);
        $this->assertEquals($student->grades->first()->id, $grading->id);
    }

    /** @test */
    public function it_can_tell_a_student_is_graded_already()
    {
        $course = factory('App\Course')->create();
        $student = factory('App\Student')->create();
        $student2 = factory('App\Student')->create();

        $course->gradeStudent($student, '2.0');
        $uni_identifier = $student->getPlainUniIdentifier();
        $different_identifier = $student2->getPlainUniIdentifier();
        $this->assertTrue($course->isGraded($uni_identifier));
        $this->assertFalse($course->isGraded($different_identifier));
    }

    /** @test */
    public function it_can_check_if_same_course_already_in_db()
    {
        $module = factory('App\Module')->create();
        $semester = 'SS 19';
        $course = new Course;

        $this->assertNull($course->duplicate($module->id, $semester));

        $course = factory('App\Course')->create([
            'module_id' => $module->id,
            'semester' => $semester
        ]);

        $fetchedCourse = $course->duplicate($module->id, $semester);

        $this->assertEquals($course->id, $fetchedCourse->id);
    }

    /** @test */
    public function it_cannnot_update_attributes_if_that_creates_a_duplication()
    {
        $module = factory('App\Module')->create();
        $semester = 'SS 19';
        $course = factory('App\Course')->create([
            'module_id' => $module->id,
            'semester' => $semester
        ]);

        $this->expectException(\Exception::class);

        $updatedCourse = $course->updateAttributes($module, $semester);
    }

    /** @test */
    public function it_can_update_its_attributes()
    {
        $module = factory('App\Module')->create();
        $semester = 'SS 19';
        $course = factory('App\Course')->create([
            'module_id' => $module->id,
            'semester' => $semester
        ]);

        $newModule = factory('App\Module')->create();
        $newSemester = 'SS 20';
        $course->updateAttributes($newModule, $newSemester);

        $this->assertEquals($course->module_id, $newModule->id);
        $this->assertEquals($course->semester, $newSemester);
    }
}
