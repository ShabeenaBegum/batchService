<?php

namespace Tests\Feature\Student;

use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\Feature\Batch\DefaultBatchDetails;
use Tests\TestCase;

class StudentBatchTest extends TestCase
{

    private $user;
    private $student;
    private $enrollId;

    protected function setUp()
    {
        parent::setUp();
        //$this->withoutExceptionHandling();
        //$this->refreshDataBase();
        $this->user = factory(User::class)->create();
        $this->actingAs($this->user, 'api');

        $this->student = factory(User::class)->create();
        $this->enrollId = getUuid();

    }

    public function test_valid_batch_is_required()
    {
        $res = $this->json("POST", route("enroll.batches.store", $this->enrollId), [
            "user_id" => $this->student->_id,
            "batch_id" => "dnkfndkf-dmflkdf"
        ]);
        $res->assertStatus(422)
            ->assertJsonValidationErrors("batch_id");
    }

    public function test_user_id_is_required()
    {
        $res = $this->json("POST", route("enroll.batches.store", $this->enrollId), [
            "user_id" => "",
            "batch_id" => "dnkfndkf-dmflkdf"
        ]);
        $res->assertStatus(422)
            ->assertJsonValidationErrors("user_id");
    }

    public function test_it_assigns_batch_to_student()
    {
        //create batch
        $batch = $this->createBatch();

        //get enroll for student
        $res = $this->json("POST", route("enroll.batches.store", $this->enrollId), [
            "user_id" => $this->student->_id,
            "batch_id" => $batch["_id"]
        ]);

        $res->assertStatus(201);

        $res->assertJson([
            "user_id" => $this->student->_id,
            "enroll_id" =>$this->enrollId,
            "batch_id" => $batch["_id"],
            "status" => config('constant.Student_batch.status.active'),
            "assigned_by" => $this->user->_id,
            "sessions" => []
        ]);

    }

    public function test_a_user_can_be_assigned_to_different_batch()
    {
        //create batch
        $batch = $this->createBatch();
        $batch2 = $this->createBatch();

        //get enroll for student
        $res = $this->json("POST", route("enroll.batches.store", $this->enrollId), [
            "user_id" => $this->student->_id,
            "batch_id" => $batch["_id"]
        ]);

        $res->assertStatus(201);

        $res->assertJson([
            "user_id" => $this->student->_id,
            "enroll_id" => $this->enrollId,
            "batch_id" => $batch["_id"],
            "status" => config('constant.Student_batch.status.active'),
            "assigned_by" => $this->user->_id,
            "sessions" => []
        ]);

        $res1 = $this->json("POST", route("enroll.batches.store", $this->enrollId), [
            "user_id" => $this->student->_id,
            "batch_id" => $batch2["_id"]
        ]);

        $res1->assertStatus(201);
        $res1->assertJson([
            "user_id" => $this->student->_id,
            "enroll_id" => $this->enrollId,
            "batch_id" => $batch2["_id"],
            "status" => config('constant.Student_batch.status.active'),
            "assigned_by" => $this->user->_id,
            "sessions" => []
        ]);

        $res2 = $this->json("GET", route("enroll.batches.index", $this->enrollId));
        $res2->assertJsonCount(2, "data");

    }

    public function test_a_it_gives_all_assigned_batches()
    {
        $res0 = $this->json("GET", route("enroll.batches.index", $this->enrollId));
        $res0->assertStatus(404);
        $res0->assertJson(["success" => false]);

        //create batch
        $batch = $this->createBatch();
        $batch2 = $this->createBatch();

        //get enroll for student
        $res = $this->json("POST", route("enroll.batches.store", $this->enrollId), [
            "user_id" => $this->student->_id,
            "batch_id" => $batch["_id"]
        ]);
        $res->assertStatus(201);

        $res1 = $this->json("POST", route("enroll.batches.store", $this->enrollId), [
            "user_id" => $this->student->_id,
            "batch_id" => $batch2["_id"]
        ]);

        $res1->assertStatus(201);

        $res2 = $this->json("GET", route("enroll.batches.index", $this->enrollId));
        $res2->assertStatus(200)->assertJsonCount(2, "data");

    }

    public function test_a_user_can_be_assigned_to_batch_only_once()
    {
        //create batch
        $batch = $this->createBatch();

        //get enroll for student
        $res = $this->json("POST", route("enroll.batches.store", $this->enrollId), [
            "user_id" => $this->student->_id,
            "batch_id" => $batch["_id"]
        ]);

        $res->assertStatus(201);

        $res1 = $this->json("POST", route("enroll.batches.store", $this->enrollId), [
            "user_id" => $this->student->_id,
            "batch_id" => $batch["_id"]
        ]);

        $res1->assertStatus(422)
            ->assertJsonValidationErrors("enroll_id");

        $res2 = $this->json("GET", route("enroll.batches.index", $this->enrollId));
        $res2->assertJsonCount(1, "data");
    }


}
