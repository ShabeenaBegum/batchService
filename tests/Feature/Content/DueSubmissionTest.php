<?php

namespace Tests\Feature\Content;

use App\User;
use Tests\Feature\Batch\DefaultBatchDetails;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DueSubmissionTest extends TestCase
{
    public $user;
    public $student;
    public $enroll_id1;
    public $enroll_id;
    public $enroll_id2;
    public $batch_details;
    public $assignment_submitted;
    public $due_submission;
    protected function setUp()
    {
        parent::setUp();
        $this->refreshDataBase();
        $this->user = factory(User::class)->create();
        $this->actingAs($this->user, 'api');
        $this->batch_details = DefaultBatchDetails::getBatch();
        $this->student1 = factory(User::class)->create();
        $this->student2 = factory(User::class)->create();
        $this->enroll_id1 = getUuid();
        $this->enroll_id2 = getUuid();
        $this->assignment_submitted = ["sessions" =>[[
            "assignments"=>[
                ["assignments_id"=>"asasndk343-43-3cds",
                    "submission_link"=>"https://github.com/hhurz/tableExport.jquery.plugin",
                    "status"=>"pending"
                ]
            ]
        ]]];
        $this->due_submission = ["data" => [
            $this->enroll_id1 =>[
                    "assignment" => [
                        [
                           1=>[ "_id"=> "sdfsdfsd-4543",
                            "name"=> "session1 ass2",
                            "marks"=> 100]
                        ]
                    ]
                ]
        ]];

        $this->due_submission_with_session_id = ["data" => [
            $this->enroll_id1 =>[
                "assignment" => [
                    [
                        1=>[ "_id"=> "sdfsdfsd-4543",
                            "name"=> "session1 ass2",
                            "marks"=> 100]
                    ],
                    []
                ]
            ],
            $this->enroll_id2 =>[
                "assignment" => [
                    [
                        [ "_id"=> "sdjasbdjasb342-4543",
                            "name"=> "session1 ass1",
                            "marks"=> 100],

                        [ "_id"=> "sdfsdfsd-4543",
                            "name"=> "session1 ass2",
                            "marks"=> 100]
                    ],
                    []
                ]
            ]
        ]];
    }


    /**
     * A basic test example.
     *
     * @return void
     * @throws \Exception
     */
    public function test_due_assignment_pass_enroll_id()
    {
        $batch = $this->json("POST",route('batch.store'), array_merge($this->batch_details,["due_date"=>"1"]));

        $student_batch = $this->json("POST", route("enroll.batches.store", $this->enroll_id1), [
            "user_id" => $this->student1->_id,
            "batch_id" => $batch->decodeResponseJson()['data']['_id']
        ]);

        $session_complete = $this->json("POST", route("session.status.store",array_merge([$batch->decodeResponseJson()['data']['sessions'][0]['_id']],["completed_date"=>"2018-03-10"])));
        $student_batch_assign = $this->json("GET", route("enroll.batches.index", $this->enroll_id1), [
            "user_id" => $this->student1->_id,
            "batch_id" => $batch->decodeResponseJson()['data']['_id']
        ]);
        $submit_assignment = $this->json('POST',route('content.store'),[
            "user_id"=>$this->student1->_id,
            "enroll_id"=> $this->enroll_id1,
            "session_id"=> $student_batch_assign->decodeResponseJson()['data'][0]['batch']['sessions'][0]['_id'],
            "submission_link"=>"https://github.com/hhurz/tableExport.jquery.plugin",
            "content_type"=>"assignments",
            "content_id"=>"sdjasbdjasb342-4543",
            "batch_id"=>$batch->decodeResponseJson()['data']['_id'],
            "submission_id"=>"dfsdfsd343-232-343"]);
        $due_submission = $this->json('GET',route('due.submission.index',"all"), ["enroll_id"=> $this->enroll_id1]);

        $this->assertCount(1,$due_submission->decodeResponseJson()['data'][$this->enroll_id1]['assignment'][0]);
        $due_submission->assertJson($this->due_submission);

    }

    public function test_due_assignment_pass_session_id()
    {
        $batch = $this->json("POST",route('batch.store'), array_merge($this->batch_details,["due_date"=>"1"]));
        $student_batch1 = $this->json("POST", route("enroll.batches.store", $this->enroll_id1), [
            "user_id" => $this->student1->_id,
            "batch_id" => $batch->decodeResponseJson()['data']['_id']
        ]);
        $student_batch2 = $this->json("POST", route("enroll.batches.store", $this->enroll_id2), [
            "user_id" => $this->student2->_id,
            "batch_id" => $batch->decodeResponseJson()['data']['_id']
        ]);
        $session_complete = $this->json("POST", route("session.status.store",
            array_merge([$batch->decodeResponseJson()['data']['sessions'][0]['_id']],["completed_date"=>"2018-03-10"])));
        $student_batch_assign1 = $this->json("GET", route("enroll.batches.index", $this->enroll_id1), [
            "user_id" => $this->student1->_id,
            "batch_id" => $batch->decodeResponseJson()['data']['_id']
        ]);
        $student_batch_assign2 = $this->json("GET", route("enroll.batches.index", $this->enroll_id2), [
            "user_id" => $this->student2->_id,
            "batch_id" => $batch->decodeResponseJson()['data']['_id']
        ]);
        /*submit assignment for student1*/
        $submit_assignment1 = $this->json('POST',route('content.store'),[
            "enroll_id"=> $this->enroll_id1,
            "user_id"=> $this->student1->_id,
            "session_id"=> $student_batch_assign1->decodeResponseJson()['data'][0]['batch']['sessions'][0]['_id'],
            "submission_link"=>"https://github.com/hhurz/tableExport.jquery.plugin",
            "content_type"=>"assignments",
            "content_id"=>"sdjasbdjasb342-4543",
            "batch_id"=>$batch->decodeResponseJson()['data']['_id'],
            "submission_id"=>"dfsdfsd343-232-343"]);
//        dd($submit_assignment1->decodeResponseJson());
        $due_submission = $this->json('GET',route('due.submission.session'),
            ["session_id"=> $batch->decodeResponseJson()['data']['sessions'][0]['_id']]);


        $this->assertCount(2,$due_submission->decodeResponseJson()['data']);
        /*student 1 assignment due count 1*/
        $this->assertCount(1,$due_submission->decodeResponseJson()['data'][0][$this->enroll_id1]['assignment'][0]);
        /*student 2 assignment due count 2*/
        $this->assertCount(2,$due_submission->decodeResponseJson()['data'][1][$this->enroll_id2]['assignment'][0]);

    }
}
