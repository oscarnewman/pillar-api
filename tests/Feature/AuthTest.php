<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use MakesGraphQLRequests, RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testLogin()
    {
        $user = factory(User::class)->create(['email' => 'testme@example.net']);

        $response = $this->graphQL(/** @lang GraphQL */ '
            mutation  {
                login(email: "testme@example.net", password: "password")
            }
        ');


        $response->assertJson([
            'data' => [
                'login' => true
            ]
        ]);

        $this->assertAuthenticatedAs($user);
    }


    public function testBadUser()
    {
        factory(User::class)->create(['email' => 'testme@example.net']);

        $response = $this->graphQL('
            mutation  {
                login(email: "testme@example.net", password: "notthepassword")
            }
        ');

        $response->assertGraphQLErrorCategory('authentication');
        $this->assertGuest();
    }

    public function testLogout()
    {
        $user = factory(User::class)->create(['email' => 'testme@example.net']);
        $this->actingAs($user)->graphQL('
        mutation {
            logout
        }');

        $this->assertGuest();
    }

    public function testRegister()
    {

        $this->graphQL('
            mutation {
                register(user: {email: "test@example.com", password: "password", firstName: "Test", lastName: "Me"}) {
                    email
                    firstName
                    lastName
                }
            }
        ')->assertJson([
            'data' => [
                'register'=> [
                    'email'  => 'test@example.com',
                    'firstName'  => 'Test',
                    'lastName'  => 'Me'
                ]
            ]
        ]);


        $this->assertGuest();
    }


}
