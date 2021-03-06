<?php

namespace Tests\Api;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use DatabaseTransactions;

    private const USER_EMAIL = 'auth.test@email.com';
    private const USER_PASSWORD = 'secret';
    private const USER_WRONG_PASSWORD = 'wrong';
    private const AUTH_ROUTE_PREFIX = 'api/auth/';

    public function setUp()
    {
        parent::setUp();

        factory($this->getModel())->create([
            'email' => self::USER_EMAIL,
        ]);
    }

    public function testLogin_Correct()
    {
        $response = $this->login(self::USER_EMAIL, self::USER_PASSWORD);

        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => ['access_token', 'token_type', 'expires_in'],
                'message',
            ])
            ->assertJson([
                'data' => [
                    'token_type' => 'bearer',
                ],
            ]);
        ;
    }

    public function testLogin_BadCredentials()
    {
        $response = $this->login(self::USER_EMAIL, self::USER_WRONG_PASSWORD);

        $response
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJsonStructure(['errors', 'message'])
            ->assertJson([
                'message' => 'Bad credentials.',
            ]);
        ;
    }

    public function testLogout_Correct()
    {
        $token = $this->getAccessToken();
        $responseAfterLogout = $this->logout($token);

        $responseAfterLogout
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['data', 'message'])
            ->assertJson([
                'message' => 'Successfully logged out.',
            ]);
        ;

        $this->assertUnauthenticated($token);
    }

    public function testGetUserInfo()
    {
        $token = $this->getAccessToken();
        $response = $this->getUserInfo($token);

        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'firstname',
                    'lastname',
                    'email',
                    'created_at',
                    'updated_at',
                ],
                'message',
            ])
            ->assertJson([
                'data' => ['email' => self::USER_EMAIL],
            ])
        ;
    }

    /**
     * @param string $token
     */
    private function assertUnauthenticated(string $token)
    {
        $unauthenticatedResponse = $this->getUserInfo($token);

        $unauthenticatedResponse
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJsonStructure(['errors', 'message'])
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
        ;
    }

    /**
     * @param string $token
     * @return TestResponse
     */
    private function getUserInfo(string $token): TestResponse
    {
        return $this->get(self::AUTH_ROUTE_PREFIX . 'user-info', $this->getHeaders($token));
    }

    /**
     * @param string $token
     * @return TestResponse
     */
    private function logout(string $token): TestResponse
    {
        return $this->post(self::AUTH_ROUTE_PREFIX . 'logout', [], $this->getHeaders($token));
    }

    /**
     * @param string $token
     * @return array
     */
    private function getHeaders(string $token): array
    {
        return [
            'Authorization' => 'Bearer ' . $token,
        ];
    }

    /**
     * @return string
     */
    private function getAccessToken(): string
    {
        $loginResponse = $this->login(self::USER_EMAIL, self::USER_PASSWORD);
        $accessToken = json_decode($loginResponse->getContent())->data->access_token;

        return $accessToken;
    }

    /**
     * @param string $email
     * @param string $password
     * @return TestResponse
     */
    private function login(string $email, string $password): TestResponse
    {
        $credentials = [
            'email' => $email,
            'password' => $password,
        ];

        return $this->post(self::AUTH_ROUTE_PREFIX . 'login', $credentials);
    }

    /**
     * @return string
     */
    private function getModel(): string
    {
        return User::class;
    }
}