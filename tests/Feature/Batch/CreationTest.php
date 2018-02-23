<?php

namespace Tests\Feature\Batch;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreationTest extends TestCase
{
    public $batch_details;
    public $user;
    public $batch_structure;

    protected function setUp()
    {
        parent::setUp();
        //$this->withoutExceptionHandling();
        $this->user = factory(User::class)->create();
        $this->batch_details = DefaultBatchDetails::getBatch();
        $this->batch_structure = DefaultBatchDetails::getBatchStructure();
        $this->actingAs($this->user, 'api');
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
        $response = $this->json("POST",'/api/batch', $this->batch_details);
        $response->assertStatus(201);
        $response->assertJson($out);
    }

    public function test_it_check_for_course_and_plan_id()
    {
        $res = $this->json("POST",'/api/batch', array_merge($this->batch_details, ["course_plan_id" => "","course_id"=>""]))
            ->assertStatus(422)
            ->assertJsonValidationErrors(["course_plan_id","course_id"]);
    }

    public function test_it_check_for_start_date()
    {
        $response = $this->json("POST",'/api/batch', array_merge($this->batch_details, ["start_date" => ""]))
            ->assertStatus(422)
            ->assertJsonValidationErrors(["start_date"]);
    }

    public function test_for_session_dates()
    {
        $response = $this->json("POST",'/api/batch', array_merge($this->batch_details,
            ["days" => [[ "day" => "thursday", "time" => "12:00"]]]
        ))
                ->assertStatus(201);
        $this->assertArraySubset($response->decodeResponseJson()['data']['days'],$this->batch_details['days']);
    }
}
