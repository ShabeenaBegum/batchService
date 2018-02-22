<?php

namespace Tests\Feature\Batch;

use App\Batch\Services\DefaultBatchDetails;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BatchTest extends TestCase
{
    public $batch_details;
    public $user;
    public $batch_structure;

    protected function  setUp()
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
        $this->batch_details = DefaultBatchDetails::getBatch();
        $this->batch_structure = DefaultBatchDetails::getBatchStructure();
    }
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_it_creates_batch()
    {
        $out = [
            "success" => true,
            "data" => $this->batch_structure
        ];
        $this->actingAs($this->user, 'api');
        $response = $this->json("POST",'/api/batch', $this->batch_details);
        $response->assertStatus(201);
        $response->assertJson($out);
    }

    public function test_it_check_for_course_plan_id()
    {
        $this->actingAs($this->user, 'api');
        $this->json("POST",'/api/batch', array_merge($this->batch_details, ["course_plan_id" => ""]))
            ->assertStatus(422)
            ->assertJsonValidationErrors(["course_plan_id"]);
    }

    public function test_it_check_for_start_date()
    {
        $this->actingAs($this->user, 'api');
        $this->json("POST",'/api/batch', array_merge($this->batch_details, ["start_date" => ""]))
            ->assertStatus(422)
            ->assertJsonValidationErrors(["start_date"]);
    }
}
