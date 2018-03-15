<?php

namespace Tests\Feature;

use App\Events\Session\RatingUpdated;
use App\Events\Student\SessionRated;
use App\User;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class RatingTest extends TestCase
{

    public $user;
    public $batch;
    private $enrollId1;
    private $enrollId2;
    private $enrollId3;
    private $enrollId4;
    private $student1;
    private $student2;
    private $student3;
    private $student4;

    protected function setUp()
    {
        parent::setUp();
        $this->refreshDataBase();
        $this->withoutExceptionHandling();
        $this->user = factory(User::class)->create();
        $this->actingAs($this->user, 'api');
        $this->batch = $this->createBatch();
        $this->enrollId1 = getUuid();
        $this->enrollId2 = getUuid();
        $this->enrollId3 = getUuid();
        $this->enrollId4 = getUuid();
        $this->student1 = getUuid();
        $this->student2 = getUuid();
        $this->student3 = getUuid();
        $this->student4 = getUuid();
    }

    /** @test */
    public function student_can_give_rating_to_session()
    {
        $session1 = $this->batch['sessions'][0];
        $session2 = $this->batch['sessions'][1];

        //Assign batch to student
        $this->assignBatchToStudent($this->batch["_id"], $this->enrollId1, $this->student1);

        //mark a session as complete
        $this->json("POST", route("session.status.store", $session1["_id"]))->assertStatus(201);

        //give rating
        $rating = [
            "rating" => 3,
            "param_1" => true,
            "param_2" => true,
            "param_3" => true,
            "param_4" => true,
            "param_5" => true,
            "comment" => "Hello World"
        ];
        $this->json(
            "POST",
            route("enroll.session.rating.store", [$this->enrollId1, $session1["_id"]]),
            $rating
        )->assertStatus(201);

        //check for rating in session
        $ratingRes = $this->json(
            "GET",
            route("enroll.session.rating.index", [$this->enrollId1, $session1["_id"]])
        )->assertStatus(200);

        $ratingRes->assertJson(["data" => ["rating" => ["rating" => $rating['rating']]]]);
        $ratingRes->assertJson(["data" => ["rating" => ["comment" => $rating['comment']]]]);
        $ratingRes->assertJson(["data" => ["rating" => $rating]]);

    }

    public function assignBatchToStudent($batchId, $enrollId, $studentId)
    {
        $res = $this->json("POST", route("enroll.batches.store", $enrollId), [
            "user_id" => $studentId,
            "batch_id" => $batchId
        ])->assertStatus(201);
    }

    /** @test */
    public function it_fires_event_when_student_gives_rating()
    {
        $session1 = $this->batch['sessions'][0];
        $session2 = $this->batch['sessions'][1];

        //Assign batch to student
        $this->assignBatchToStudent($this->batch["_id"], $this->enrollId1, $this->student1);

        //mark a session as complete
        $this->json("POST", route("session.status.store", $session1["_id"]))->assertStatus(201);

        //give rating
        $rating = [
            "rating" => 3,
            "param_1" => true,
            "param_2" => true,
            "param_3" => true,
            "param_4" => true,
            "param_5" => true,
        ];
        Event::fake();

        $this->json(
            "POST",
            route("enroll.session.rating.store", [$this->enrollId1, $session1["_id"]]),
            $rating
        )->assertStatus(201);

        Event::assertDispatched(SessionRated::class);
    }

    /** @test */
    public function all_rating_is_reflected_as_avg_in_session()
    {

        $session1 = $this->batch['sessions'][0];
        $session2 = $this->batch['sessions'][1];

        //Assign batch to student1, student2
        $this->assignBatchToStudent($this->batch["_id"], $this->enrollId1, $this->student1);
        $this->assignBatchToStudent($this->batch["_id"], $this->enrollId2, $this->student2);
        $this->assignBatchToStudent($this->batch["_id"], $this->enrollId3, $this->student3);

        //mark a session as complete
        $this->json("POST", route("session.status.store", $session1["_id"]))->assertStatus(201);

        //give rating for student1, student2
        $rating1 = [
            "rating" => 3,
            "comment" => "hello"
        ];
        $this->json(
            "POST",
            route("enroll.session.rating.store", [$this->enrollId1, $session1["_id"]]),
            $rating1
        )->assertStatus(201);

        $rating2 = [
            "rating" => 4,
        ];
        $this->json(
            "POST",
            route("enroll.session.rating.store", [$this->enrollId2, $session1["_id"]]),
            $rating2
        )->assertStatus(201);

        $rating3 = [
            "rating" => 3,
            "comment" => "Hello Laravel"
        ];
        $this->json(
            "POST",
            route("enroll.session.rating.store", [$this->enrollId3, $session1["_id"]]),
            $rating3
        )->assertStatus(201);

        //check for rating in session level and rating should be avg of two
        $res = $this->json("GET", route("session.rating", $session1["_id"]))->assertStatus(200);
        $ratings = collect([$rating1, $rating2, $rating3]);

        $res->assertJson(["data" => [
            "rating" => $ratings->sum('rating'),
            "rating_count" => $ratings->count(),
            "comment_count" => $ratings->where("comment", "!=", "")->count(),
            "rating_avg" => round($ratings->avg('rating'), 1)
        ]]);
    }

    /** @test */
    public function all_rating_is_reflected_as_avg_in_batch()
    {

        //create batch1
        $session1 = $this->batch['sessions'][0];
        $session2 = $this->batch['sessions'][1];


        //Assign batch to student1, student2
        $this->assignBatchToStudent($this->batch["_id"], $this->enrollId1, $this->student1);
        $this->assignBatchToStudent($this->batch["_id"], $this->enrollId2, $this->student2);


        //mark a session1 as complete
        $this->json("POST", route("session.status.store", $session1["_id"]))->assertStatus(201);
        //mark a session2 as complete
        $this->json("POST", route("session.status.store", $session2["_id"]))->assertStatus(201);


        //give rating for student1 for session1
        $rating11 = [
            "rating" => 4,
            "comment" => "hello"
        ];
        $this->json(
            "POST",
            route("enroll.session.rating.store", [$this->enrollId1, $session1["_id"]]),
            $rating11
        )->assertStatus(201);

        //give rating for student2 for session1
        $rating12 = [
            "rating" => 4,
            "comment" => ""
        ];
        $this->json(
            "POST",
            route("enroll.session.rating.store", [$this->enrollId2, $session1["_id"]]),
            $rating12
        )->assertStatus(201);

        //give rating for student1 for session2
        $rating21 = [
            "rating" => 3,
            "comment" => "hello"
        ];
        $this->json(
            "POST",
            route("enroll.session.rating.store", [$this->enrollId1, $session2["_id"]]),
            $rating21
        )->assertStatus(201);

        //give rating for student2 for session2
        $rating22 = [
            "rating" => 3,
            "comment" => ""
        ];
        $this->json(
            "POST",
            route("enroll.session.rating.store", [$this->enrollId2, $session2["_id"]]),
            $rating22
        )->assertStatus(201);

        //check for rating in session level and rating should be avg of two
        //check for rating in session level and rating should be avg of two
        $res = $this->json("GET", route("session.rating", $session1["_id"]))->assertStatus(200);
        $ratings = collect([$rating11, $rating12]);

        $res->assertJson(["data" => [
            "rating" => $ratings->sum('rating'),
            "rating_count" => $ratings->count(),
            "comment_count" => $ratings->where("comment", "!=", "")->count(),
            "rating_avg" => round($ratings->avg('rating'), 1)
        ]]);

        $res = $this->json("GET", route("session.rating", $session2["_id"]))->assertStatus(200);
        $ratings = collect([$rating21, $rating22]);

        $res->assertJson(["data" => [
            "rating" => $ratings->sum('rating'),
            "rating_count" => $ratings->count(),
            "comment_count" => $ratings->where("comment", "!=", "")->count(),
            "rating_avg" => round($ratings->avg('rating'), 1)
        ]]);

        //check for rating in batch level and rating should be avg of two session avg
        $res = $this->json("GET", route("batch.rating", $this->batch["_id"]))->assertStatus(200);
        $bratings = collect([$rating11, $rating12,$rating21, $rating22]);

        $res->assertJson(["data" => [
            "rating" => $bratings->sum('rating'),
            "rating_count" => $bratings->count(),
            "comment_count" => $bratings->where("comment", "!=", "")->count(),
            "rating_avg" => round($bratings->avg('rating'), 1)
        ]]);
    }
}
