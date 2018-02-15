<?php

namespace Tests\Feature;

use App\Notifications\Test;
use App\User;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ExampleTest extends TestCase
{

    public $user;

    protected function setUp()
    {
        parent::setUp();

        $this->withExceptionHandling();

        $this->refreshDataBase();

        $this->user = factory(User::class)->create();
    }
    /**
     * A basic test example.
     * @return void
     */
    public function test_notification_is_sent()
    {
        Notification::fake();

        $this->get('/test');

        Notification::assertSentTo([$this->user], Test::class);
    }

    public function test_It_gives_unauthenticated_error()
    {

        $this->json("GET",'/api/user')
                ->assertStatus(401)
                ->assertJson([
                    "success" => false,
                    "data" => [
                        'message' => "Unauthenticated."
                    ]
                ]);
    }

    public function test_It_gives_user_details_if_authenticated()
    {

        $this->actingAs($this->user, 'api');

        $response = $this->json('GET','/api/user');

        $response->assertJsonFragment([
                'name' => $this->user->name,
                'email' => $this->user->email,
            ]);
    }

}
