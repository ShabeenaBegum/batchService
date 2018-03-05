<?php

namespace Tests\Feature;

use App\Events\Session\Completed;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SessionStatusTest extends TestCase
{
    private $user;
    private $student;
    private $enrollId;

    protected function setUp()
    {
        parent::setUp();
        //$this->withoutExceptionHandling();
        $this->refreshDataBase();
        $this->user = factory(User::class)->create();
        $this->actingAs($this->user, 'api');

        $this->student = factory(User::class)->create();
        $this->enrollId = getUuid();

    }
    public function test_it_marks_session_status_as_complete()
    {
        //create batch
        $batch = $this->createBatch();
        $this->assertCount(2, $batch['sessions']);
        $session = $batch['sessions'][0];

        //update a session
        $res = $this->json("POST", route("session.status.store", $session["_id"]));
        $res->assertStatus(201);
        $res->assertJson(["data" => ["status" => config('constant.session.status.completed')]]);


        //assert status is completed
        $res1 = $this->json("GET", route("session.status.index", $batch['sessions'][0]["_id"]));
        $res1->assertStatus(200);
        $res1->assertJson([
            "data" => [
                "status" => config('constant.session.status.completed'),
                "_id"    => $batch['sessions'][0]["_id"]
            ]
        ]);

    }

    public function test_it_fires_event_after_status_is_marked()
    {
        //create batch
        $batch = $this->createBatch();

        $this->assertCount(2, $batch['sessions']);
        $session = $batch['sessions'][0];

        Event::fake();
        //update a session
        $res = $this->json("POST", route("session.status.store", $session["_id"]));
        Event::assertDispatched(Completed::class);
        Event::assertDispatched(Completed::class, function ($e) use ($session) {
            return $e->session->_id == $session['_id'];
        });
    }

    public function test_it_creates_sessions_in_student_sessions()
    {
        //create batch
        $batch = $this->createBatch();
        $this->assertCount(2, $batch['sessions']);
        $session1 = $batch['sessions'][0];
        $session2 = $batch['sessions'][1];

        //Assign students to batch
        $this->json("POST", route("enroll.batches.store", $this->enrollId), [
            "user_id" => $this->student->_id,
            "batch_id" => $batch["_id"]
        ])->assertStatus(201);

        $this->json("POST", route("session.status.store", $session1["_id"]));
        $res2 = $this->json("GET", route("enroll.batches.show", [$this->enrollId, $batch['_id']]))
                        ->assertStatus(200);
        $res2 = $res2->decodeResponseJson("data");
        $this->assertCount(1, $res2['sessions']);

        $this->json("POST", route("session.status.store", $session2["_id"]));
        $this->json("POST", route("session.status.store", $session2["_id"]));

        $res22 = $this->json("GET", route("enroll.batches.show", [$this->enrollId, $batch['_id']]))
            ->assertStatus(200);

        $res22 = $res22->decodeResponseJson("data");
        $this->assertCount(2, $res22['sessions']);

    }


}
