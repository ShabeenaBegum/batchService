<?php

namespace Tests\Feature\Student;

use App\Events\Student\Session\AttendanceMarked;
use App\User;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttendanceTest extends TestCase
{
    private $user;
    private $student;
    private $enrollId;

    /**
     * @return array
     * @throws \Exception
     */
    public function createBatchAssignCompleteSession()
    {
        //create batch
        $batch = $this->createBatch();
        $this->assertCount(2, $batch['sessions']);
        $session1 = $batch['sessions'][0];
        $session2 = $batch['sessions'][1];

        //assign student
        $studentBatchAssigned = $this->json("POST", route("enroll.batches.store", $this->enrollId), [
            "user_id" => $this->student->_id,
            "batch_id" => $batch["_id"]
        ])->assertStatus(201);

        $this->json("POST", route("session.status.store", $session1["_id"]));

        $res2 = $this->json("GET", route("enroll.batches.show", [$this->enrollId, $batch['_id']]))
            ->assertStatus(200);
        $res2 = $res2->decodeResponseJson("data");
        $this->assertCount(1, $res2['sessions']);

        return array($session1, $session2);
    }

    protected function setUp()
    {
        parent::setUp();
        $this->refreshDataBase();
        $this->user = factory(User::class)->create();
        $this->actingAs($this->user, 'api');

        $this->student = factory(User::class)->create();
        $this->enrollId = getUuid();

    }


    public function test_it_returns_default_attendance_for_a_given_session()
    {
        list($session1, $session2) = $this->createBatchAssignCompleteSession();

        //request for attendance
        $s1attendance = $this->json(
            "GET",
            route("enroll.session.attendance.index",[$this->enrollId, $session1['_id']])
        )->assertStatus(200)->decodeResponseJson("data");

        $this->assertEquals(config('constant.session.attendance.pending'), $s1attendance['attendance']);

        $s1attendance = $this->json(
            "GET", route("enroll.session.attendance.index",[$this->enrollId, $session2['_id']])
        )->assertStatus(404);
        
    }

    public function test_it_marks_attendance_for_a_given_session()
    {
        list($session1, $session2) = $this->createBatchAssignCompleteSession();

        //Add attendance
        $s1attendance = $this->json(
            "POST",
            route("enroll.session.attendance.index",[$this->enrollId, $session1['_id']]),
            ["attendance" => "present"]
        )->assertStatus(200)->decodeResponseJson("data");
        $this->assertEquals(config('constant.session.attendance.present'), $s1attendance['attendance']);

        //Add attendance
        $s1attendance = $this->json(
            "POST",
            route("enroll.session.attendance.index",[$this->enrollId, $session1['_id']]),
            ["attendance" => "absent"]
        )->assertStatus(200)->decodeResponseJson("data");
        $this->assertEquals(config('constant.session.attendance.absent'), $s1attendance['attendance']);
        //TODO ADD WHO MARKED ATTENDANCE

    }

    public function test_events_are_fired_when_attendance_is_marked()
    {
        list($session1, $session2) = $this->createBatchAssignCompleteSession();
        Event::fake();

        //Add attendance
        $s1attendance = $this->json(
            "POST",
            route("enroll.session.attendance.index",[$this->enrollId, $session1['_id']]),
            ["attendance" => "present"]
        )->assertStatus(200)->decodeResponseJson("data");
        $this->assertEquals(config('constant.session.attendance.present'), $s1attendance['attendance']);

        Event::assertDispatched(AttendanceMarked::class);
        Event::assertDispatched(AttendanceMarked::class, function ($e) use ($session1) {
            return $e->session->session_id == $session1['_id'];
        });
    }

    //TODO batch, enroll, session tests
}
