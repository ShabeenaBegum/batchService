<?php

namespace Tests\Feature\Student;

use App\Events\Student\Batch\BatchTransferred;
use App\Student\Models\StudentBatch;
use App\User;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class StudentBatchTransferTest extends TestCase
{
    private $user;
    private $student;
    private $enrollId;

    protected function setUp()
    {
        parent::setUp();
        $this->refreshDataBase();
        $this->user = factory(User::class)->create();
        $this->actingAs($this->user, 'api');

        $this->student = factory(User::class)->create();
        $this->enrollId = getUuid();

    }

    public function test_a_valid_from_batch_is_required()
    {
        $transfer = $this->json(
            "POST",
            route("enroll.transfer", [$this->enrollId]),
            [
                "from_batch" => "ekndfdf",
                "to_batch" => "",
                "reason" => ""
            ]
        );
        $transfer->assertStatus(422);
        $transfer->assertJsonValidationErrors("from_batch");
    }

    public function test_a_valid_to_batch_is_required()
    {
        $batch1 = $this->createBatch();

        $transfer = $this->json(
            "POST",
            route("enroll.transfer", [$this->enrollId]),
            [
                "from_batch" => $batch1["_id"],
                "to_batch" => "dmkmdkf-admkmd",
                "reason" => ""
            ]
        );
        $transfer->assertStatus(422);
        $transfer->assertJsonValidationErrors("to_batch");
    }

    public function test_reason_is_required()
    {
        $batch1 = $this->createBatch();
        $batch2 = $this->createBatch();

        $transfer = $this->json(
            "POST",
            route("enroll.transfer", [$this->enrollId]),
            [
                "from_batch" => $batch1["_id"],
                "to_batch" => $batch2["_id"],
                "reason" => ""
            ]
        );
        $transfer->assertStatus(422);
        $transfer->assertJsonValidationErrors("reason");
    }

    public function test_both_to_and_from_batch_must_be_different()
    {
        $batch1 = $this->createBatch();

        $transfer = $this->json(
            "POST",
            route("enroll.transfer", [$this->enrollId]),
            [
                "from_batch" => $batch1["_id"],
                "to_batch" => $batch1["_id"],
                "reason" => "some reason"
            ]
        );

        $transfer->assertStatus(422);
        $transfer->assertJsonValidationErrors("to_batch");
        $transfer->assertJsonValidationErrors("from_batch");
    }

    public function test_student_can_be_transferred_to_another_batch_with_same_cp_and_not_started()
    {
        //create 2 batches
        $batch1 = $this->createBatch();
        $batch1session1 = $batch1['sessions'][0];
        $batch1session2 = $batch1['sessions'][1];

        $batch2 = $this->createBatch();
        $batch2session1 = $batch2['sessions'][0];
        $batch2session2 = $batch2['sessions'][1];

        //assign batch to 1st batch
        $studentBatchAssigned = $this->json("POST", route("enroll.batches.store", $this->enrollId), [
            "user_id" => $this->student->_id,
            "batch_id" => $batch1["_id"]
        ]);

        //assert batch is assigned
        $studentBatchAssigned->assertStatus(201);
        $studentBatchAssigned->assertJson([
            "batch_id" => $batch1["_id"],
            "status" => config('constant.batch.status.active')
        ]);

        Event::fake();

        //transfer to 2nd batch
        $transfer = $this->json(
            "POST",
            route("enroll.transfer", [$this->enrollId]),
            [
                "from_batch" => $batch1['_id'],
                "to_batch" => $batch2['_id'],
                "reason" => "dummy reason"
            ]
        );

        Event::assertDispatched(BatchTransferred::class);
        Event::assertDispatched(BatchTransferred::class, function ($e) use ($batch1, $batch2) {
            return $e->from->batch_id == $batch1['_id'] && $e->to->batch_id == $batch2['_id'];
        });

        //assert 1st batch status is transferred
        $currentBatch = StudentBatch::where("enroll_id", $this->enrollId)
            ->where("batch_id", $batch1['_id'])->firstOrFail();
        $this->assertEquals(config('constant.batch.status.transferred'), $currentBatch->status);

        //assert 2nd batch is assigned
        $currentBatch = StudentBatch::where("enroll_id", $this->enrollId)
            ->where("batch_id", $batch2['_id'])->firstOrFail();
        $this->assertEquals(config('constant.batch.status.active'), $currentBatch->status);

    }

    public function test_student_can_be_transferred_to_another_batch_with_same_cp_and_session_started()
    {
        //create 2 batches
        $batch1 = $this->createBatch();
        $batch1session1 = $batch1['sessions'][0];
        $batch1session2 = $batch1['sessions'][1];

        $batch2 = $this->createBatch();
        $batch2session1 = $batch2['sessions'][0];
        $batch2session2 = $batch2['sessions'][1];

        //assign batch to 1st batch
        $studentBatchAssigned = $this->json("POST", route("enroll.batches.store", $this->enrollId), [
            "user_id" => $this->student->_id,
            "batch_id" => $batch1["_id"]
        ]);

        //mark session complete
        $this->json("POST", route("session.status.store", $batch1session1["_id"]));

        $s1attendance = $this->json(
            "POST",
            route("enroll.session.attendance.index", [$this->enrollId, $batch1session1['_id']]),
            ["attendance" => "present"]
        )->assertStatus(200);

        $this->json("POST", route("session.status.store", $batch1session2["_id"]));

        //assert batch is assigned
        $studentBatchAssigned->assertStatus(201);
        $studentBatchAssigned->assertJson([
            "batch_id" => $batch1["_id"],
            "status" => config('constant.batch.status.active')
        ]);

        //transfer to 2nd batch
        $transfer = $this->json(
            "POST",
            route("enroll.transfer", [$this->enrollId]),
            [
                "from_batch" => $batch1['_id'],
                "to_batch" => $batch2['_id'],
                "reason" => "dummy reason"
            ]
        );

        //assert 1st batch status is transferred
        $currentBatch = StudentBatch::where("enroll_id", $this->enrollId)
            ->where("batch_id", $batch1['_id'])->firstOrFail();
        $this->assertEquals(config('constant.batch.status.transferred'), $currentBatch->status);

        //assert 2nd batch is assigned
        $newBatch = StudentBatch::where("enroll_id", $this->enrollId)
            ->where("batch_id", $batch2['_id'])->firstOrFail();
        $this->assertEquals(config('constant.batch.status.active'), $newBatch->status);

        $currentBatchSessions = $currentBatch->sessions->toArray();
        $newBatchSessions = $newBatch->sessions->toArray();

        //assert session_status, attendance are same as older sessions
        foreach ($currentBatchSessions as $index => $session) {
            $this->assertEquals($currentBatchSessions[$index]['session_status'], $newBatchSessions[$index]['session_status']);
            $this->assertEquals($currentBatchSessions[$index]['attendance'], $newBatchSessions[$index]['attendance']);
        }

    }

    public function test_student_can_be_transferred_to_another_batch_with_different_cp()
    {
        //create 2 batches
        $batch1 = $this->createBatch();
        $batch1session1 = $batch1['sessions'][0];
        $batch1session2 = $batch1['sessions'][1];

        $batch2 = $this->createBatch(['course_plan_id' => "1113"]);
        $batch2session1 = $batch2['sessions'][0];
        $batch2session2 = $batch2['sessions'][1];

        //assign batch to 1st batch
        $studentBatchAssigned = $this->json("POST", route("enroll.batches.store", $this->enrollId), [
            "user_id" => $this->student->_id,
            "batch_id" => $batch1["_id"]
        ]);

        //mark session complete
        $this->json("POST", route("session.status.store", $batch1session1["_id"]));

        $s1attendance = $this->json(
            "POST",
            route("enroll.session.attendance.index", [$this->enrollId, $batch1session1['_id']]),
            ["attendance" => "present"]
        )->assertStatus(200);

        $this->json("POST", route("session.status.store", $batch1session2["_id"]));

        //assert batch is assigned
        $studentBatchAssigned->assertStatus(201);
        $studentBatchAssigned->assertJson([
            "batch_id" => $batch1["_id"],
            "status" => config('constant.batch.status.active')
        ]);

        Event::fake();
        //transfer to 2nd batch
        $transfer = $this->json(
            "POST",
            route("enroll.transfer", [$this->enrollId]),
            [
                "from_batch" => $batch1['_id'],
                "to_batch" => $batch2['_id'],
                "reason" => "dummy reason"
            ]
        );

        //assert 1st batch status is transferred
        $currentBatch = StudentBatch::where("enroll_id", $this->enrollId)
            ->where("batch_id", $batch1['_id'])->firstOrFail();
        $this->assertEquals(config('constant.batch.status.transferred'), $currentBatch->status);

        //assert 2nd batch is assigned
        $newBatch = StudentBatch::where("enroll_id", $this->enrollId)
            ->where("batch_id", $batch2['_id'])->firstOrFail();

        $this->assertEquals(config('constant.batch.status.active'), $newBatch->status);
        $this->assertNotEquals($currentBatch->batch_id, $newBatch->batch_id);

        Event::assertDispatched(BatchTransferred::class);

        Event::assertDispatched(BatchTransferred::class, function ($e) use ($batch1, $batch2) {
            return $e->from->batch_id == $batch1['_id'] && $e->to->batch_id == $batch2['_id'];
        });

        //TODO: Check for both batch course_plan_id and it should be different
    }
}
