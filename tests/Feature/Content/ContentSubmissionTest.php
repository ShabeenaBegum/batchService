<?php

namespace Tests\Feature\Content;

use App\User;
use Carbon\Carbon;
use Tests\Feature\Batch\DefaultBatchDetails;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContentSubmissionTest extends TestCase
{
    public $user;
    public $student;
    public $enroll_id;
    public $batch_details;
    public $assignment_submitted;
    protected function setUp()
    {
        parent::setUp();
        //$this->withoutExceptionHandling();
        $this->refreshDataBase();
        $this->user = factory(User::class)->create();
        $this->actingAs($this->user, 'api');
        $this->batch_details = DefaultBatchDetails::getBatch();
        $this->student = factory(User::class)->create();
        $this->enroll_id = getUuid();
        $this->assignment_submitted = ["sessions" =>[[
                            "assignments"=>[
                                ["assignments_id"=>"asasndk343-43-3cds",
                                    "submission_link"=>"https://github.com/hhurz/tableExport.jquery.plugin",
//                                "submission_date"=>(string)Carbon::now(),
                                "status"=>"pending"
                                ]
                            ]
        ]]];


    }

    /**
     * A basic test example.
     *
     * @return void
     * @throws \Exception
     */
    public function test_assignment_submitted()
    {
        $batch = $this->json("POST",route('batch.store'), $this->batch_details);
        $student_batch = $this->json("POST", route("enroll.batches.store", $this->enroll_id), [
            "user_id" => $this->student->_id,
            "batch_id" => $batch->decodeResponseJson()['data']['_id']
        ]);
        $session_complete = $this->json("POST", route("session.status.store",           $batch->decodeResponseJson()['data']['sessions'][0]['_id']));
        $student_batch_session_competed = $this->json("GET", route("enroll.batches.index", $this->enroll_id), [
            "user_id" => $this->student->_id,
            "batch_id" => $batch->decodeResponseJson()['data']['_id']
        ]);
        $res = $this->json('POST',route('content.store'),[
            "user_id"=>$this->student->_id,
            "enroll_id"=> $this->enroll_id,
            "session_id"=> $student_batch_session_competed->decodeResponseJson()['data'][0]['sessions'][0]['session_id'],
            "submission_link"=>"https://github.com/hhurz/tableExport.jquery.plugin",
            "content_type"=>"assignments",
            "content_id"=>"asasndk343-43-3cds"]);
        $res->assertJson(["data"=>$this->assignment_submitted]);
    }

    /**
     * @throws \Exception
     */
    public function test_check_same_assignment_not_submitted_again()
    {
        $batch = $this->json("POST",route('batch.store'), $this->batch_details);
        $student_batch = $this->json("POST", route("enroll.batches.store", $this->enroll_id), [
            "user_id" => $this->student->_id,
            "batch_id" => $batch->decodeResponseJson()['data']['_id']
        ]);
        $session_complete = $this->json("POST", route("session.status.store",           $batch->decodeResponseJson()['data']['sessions'][0]['_id']));
        $student_batch_session_competed = $this->json("GET", route("enroll.batches.index", $this->enroll_id), [
            "user_id" => $this->student->_id,
            "batch_id" => $batch->decodeResponseJson()['data']['_id']
        ]);
        $res1 = $this->json('POST',route('content.store'),[
            "user_id"=>$this->student->_id,
            "enroll_id"=> $this->enroll_id,
            "session_id"=> $student_batch_session_competed->decodeResponseJson()['data'][0]['sessions'][0]['session_id'],
            "submission_link"=>"https://github.com/hhurz/tableExport.jquery.plugin",
            "content_type"=>"assignments",
            "content_id"=>"asasndk343-43-3cds"]);
        /*submit same assignment again*/
        $res2 = $this->json('POST',route('content.store'),[
            "user_id"=>$this->student->_id,
            "enroll_id"=> $this->enroll_id,
            "session_id"=> $student_batch_session_competed->decodeResponseJson()['data'][0]['sessions'][0]['session_id'],
            "submission_link"=>"https://github.com/hhurz/tableExport.jquery.plugin",
            "content_type"=>"assignments",
            "content_id"=>"asasndk343-43-3cds"]);
        $res2->assertJsonValidationErrors(['content_id']);
    }

}
