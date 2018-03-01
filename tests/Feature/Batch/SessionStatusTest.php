<?php

namespace Tests\Feature\Batch;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SessionStatusTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public $batch_details;
    public $user;
    public $session_cancel_update;
    public $session_cancel_no_change_date_update;
    public $session_cancel_given_date_update;
    protected function setUp()
    {
        parent::setUp();
        $this->refreshDataBase();
        $this->user = factory(User::class)->create();
        $this->actingAs($this->user, 'api');
        $this->batch_details = DefaultBatchDetails::getBatch();
        $this->session_cancel_update = DefaultBatchDetails::sessionCancelUpdated();
        $this->session_cancel_no_change_date_update = DefaultBatchDetails::sessionCancelUpdatedNoDateChange();
        $this->session_cancel_given_date_update = DefaultBatchDetails::sessionCancelUpdatedWithDateGiven();
    }
    public function test_session_cancel()
    {
        $batch = $this->json("POST",route('batch.store'), $this->batch_details);
//        dump($batch->decodeResponseJson()['data']);
        $response = $this->json("PUT",route('session.update',$batch->decodeResponseJson()['data']['sessions'][0]['_id']),["reason"=>"No Mentor","change_date"=>1,"approved_by"=>"1234-343","session_id"=>$batch->decodeResponseJson()['data']['sessions'][0]['_id'],"requested_by"=>"1234-343"]);
        $response->assertJson(["data"=>$this->session_cancel_update]);

    }

    public function test_session_cancel_dont_change_date()
    {
        $batch = $this->json("POST",route('batch.store'), $this->batch_details);
//        dump($batch->decodeResponseJson()['data']);
        $response = $this->json("PUT",route('session.update',$batch->decodeResponseJson()['data']['sessions'][0]['_id']),["reason"=>"No Mentor","change_date"=>0,"approved_by"=>"1234-343","session_id"=>$batch->decodeResponseJson()['data']['sessions'][0]['_id'],"requested_by"=>"1234-343"]);
        $response->assertJson(["data"=>$this->session_cancel_no_change_date_update]);

    }

    public function test_session_cancel_pass_date()
    {
        $batch = $this->json("POST",route('batch.store'), $this->batch_details);
        $response = $this->json("PUT",route('session.update',$batch->decodeResponseJson()['data']['sessions'][0]['_id']),["reason"=>"No Mentor","change_date"=>1,"approved_by"=>"1234-343","session_id"=>$batch->decodeResponseJson()['data']['sessions'][0]['_id'],"requested_by"=>"1234-343","session_date"=>"2018-03-30","session_time"=>"12:00"]);
        $response->assertJson(["data"=>$this->session_cancel_given_date_update]);

    }
}
