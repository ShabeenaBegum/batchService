<?php

namespace Tests\Feature\Batch;

use App\User;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdationTest extends TestCase
{
    public $user;
    public $update_data;
    public $updated_data;
    public $create_data;
    public $batch_structure;
    public $today_date;
    protected function setUp()
    {
        parent::setUp();
//        $this->withoutExceptionHandling();
        $this->user = factory(User::class)->create();
        $this->actingAs($this->user, 'api');
        $this->create_data = DefaultBatchDetails::getBatch();
        $this->update_data = DefaultBatchDetails::getUpdateDetails();
        $this->updated_data = DefaultBatchDetails::getBatchUpdated();
        $this->course_plan_update_data = DefaultBatchDetails::getCoursePlanUpdated();
        $this->update_date_changes = DefaultBatchDetails::getStartDateChange();
        $this->batch_structure = DefaultBatchDetails::getBatchStructure();
        $this->today_date = Carbon::parse("this thursday")->format("Y-m-d");
        $this->extra_session = DefaultBatchDetails::extraSessionDetails();
        $this->extra_session_updated = DefaultBatchDetails::extraSessionUpdated();
    }

    /**
     * A basic test example.
     *
     * @return void
     * @throws \Exception
     */
    public function test_if_updates_batch()
    {
        $create_batch = $this->json('POST','/api/batch',array_merge($this->create_data,["course_plan_id"=>"1122","start_date"=>$this->today_date,"status"=>"yet_to_start"]));
        $create_batch->assertStatus(201);
        $res = $this->json('PUT','/api/batch/'.$create_batch->decodeResponseJson()['data']['_id'],array_merge($this->update_data, ["course_plan_id"=>"1122",$this->today_date]));
        $res->assertStatus(200)->assertJson(["success" => true,
            "data" =>$this->updated_data]);
    }

    public function test_if_has_all_details()
    {
        $create_batch = $this->json('POST','/api/batch',$this->create_data);
        $create_batch->assertStatus(201);
        $create_batch->assertJson([ "success" => true,
            "data" => $this->batch_structure]);
        $res = $this->json('PUT','/api/batch/'.$create_batch->decodeResponseJson()['data']['_id'],array_merge($this->update_data,["course_plan_id"=>""]));
        $res->assertJsonValidationErrors(['course_plan_id']);
    }

    public function test_if_doesnot_allow_to_change_course_plan_id_if_batch_started()
    {
        $create_batch = $this->json('POST','/api/batch',array_merge($this->create_data,["course_plan_id"=>"1111"]));
        $create_batch->assertStatus(201);
        $res = $this->json('PUT','/api/batch/'.$create_batch->decodeResponseJson()['data']['_id'],                          array_merge($this->update_data,["course_plan_id"=>"1124"]));
        $res->assertJsonValidationErrors(['course_plan_id']);
    }

    public function test_to_check_course_plan_id_change()
    {
        $create_batch = $this->json('POST','/api/batch',array_merge($this->create_data,["course_plan_id"=>"1122","start_date"=>$this->today_date,"status"=>"yet_to_start"]));
        $res = $this->json('PUT','/api/batch/'.$create_batch->decodeResponseJson()['data']['_id'],                          array_merge($this->update_data,["course_plan_id"=>"1124"]));
        $res->assertJson(["success"=>"true","data"=>$this->course_plan_update_data]);
    }

    public function test_to_not_change_course_plan_id_if_no_course_session_details_passed()
    {
        $create_batch = $this->json('POST','/api/batch',array_merge($this->create_data,["course_plan_id"=>"1122","start_date"=>$this->today_date,"status"=>"yet_to_start"]));
        $res = $this->json('PUT','/api/batch/'.$create_batch->decodeResponseJson()['data']['_id'],                          array_merge($this->update_data,["course_plan_id"=>"1124","course_session_details"=>""]));
        $res->assertJsonValidationErrors(['course_session_details']);
    }

    public function test_to_check_start_date_change()
    {
        $create_batch = $this->json('POST','/api/batch',array_merge($this->create_data, ["start_date"=>$this->today_date,"status"=>"yet_to_start"]));
        $res = $this->json('PUT','/api/batch/'.$create_batch->decodeResponseJson()['data']['_id'],array_merge($this->update_data,["start_date"=>$this->today_date]));
        $res->assertJson(["data"=>$this->update_date_changes]);
    }

    public function test_check_extra_session_updated()
    {
        $create_batch = $this->json('POST','/api/batch',array_merge($this->create_data, ["start_date"=>$this->today_date,"status"=>"yet_to_start"]));
        $res = $this->json('PATCH','/api/batch/extrasession/'.$create_batch->decodeResponseJson()['data']['_id'],array_merge($this->extra_session,["module_id"=>$create_batch->decodeResponseJson()['data']['course_session_details']['modules'][0]['_id'],"after_session_id"=>$create_batch->decodeResponseJson()['data']['course_session_details']['modules'][0]['session_list'][0]['_id']]));
//        dump($create_batch->decodeResponseJson()['data']); "after_session_id"=>$create_batch->decodeResponseJson()['data']['course_session_details']['modules'][0]['session_list'][0]['_id']+
//        dump($res->content());
        $res->assertJson(["data"=>$this->extra_session_updated]);
    }
}
