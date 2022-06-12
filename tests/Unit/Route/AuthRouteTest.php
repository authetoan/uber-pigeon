<?php

namespace Tests\Unit\Route;

use App\Http\Controllers\AuthController;
use App\Models\User;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Mockery;
use Mockery\MockInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Tests\TestCase;

class AuthRouteTest extends TestCase
{
    public function testLoginRouteSuccess()
    {
        $mock= Mockery::mock('Eloquent', 'alias:\App\Models\User');
        $mock->shouldReceive('where->first')
            ->once()
            ->andSet('password', Hash::make("123456"))
            ->andReturnSelf();
        $mock->shouldReceive('where->createToken')
            ->andSet('plainTextToken', "random-token")
            ->once()
            ->andReturnSelf();
        $this->app->instance('alias:\App\Models\User', $mock);
        Hash::shouldReceive('check')->once()->andReturn(true);
        $request = new Request();
        $request->setMethod("POST");
        $request->request->set("email", "test@test.com");
        $request->request->set("password", 123456);
        $request->request->set("device_name", "PC");
        $authController = new AuthController();
        $response = $authController->createToken($request);
        $this->assertEquals($response->status(), 200);
    }

    public function testLoginRouteFailPassword()
    {
        $mock= Mockery::mock('Eloquent', 'alias:\App\Models\User');
        $mock->shouldReceive('where->first')
            ->once()
            ->andSet('password', Hash::make("123456"))
            ->andReturnSelf();
        $this->app->instance('alias:\App\Models\User', $mock);
        Hash::shouldReceive('check')->once()->andReturn(false);
        $request = new Request();
        $request->setMethod("POST");
        $request->request->set("email", "test@test.com");
        $request->request->set("password", 123456);
        $request->request->set("device_name", "PC");
        $authController = new AuthController();
        $response = $authController->createToken($request);
        $this->assertEquals($response->status(), 401);
    }

    public function testLoginRouteFailUsername()
    {
        $mock= Mockery::mock('Eloquent', 'alias:\App\Models\User');
        $mock->shouldReceive('where->first')
            ->once()
            ->andSet('password', Hash::make("123456"))
            ->andReturnNull();
        $this->app->instance('alias:\App\Models\User', $mock);
        $request = new Request();
        $request->setMethod("POST");
        $request->request->set("email", "test@test.com");
        $request->request->set("password", 123456);
        $request->request->set("device_name", "PC");
        $authController = new AuthController();
        $response = $authController->createToken($request);
        $this->assertEquals($response->status(), 401);
    }

    public function testRevokeRoute()
    {
        $mock= Mockery::mock('Eloquent', 'alias:\App\Models\User');
        $mock->shouldReceive('tokens->delete')
            ->once()
            ->andReturnTrue();
        Auth::shouldReceive('user')->once()->andReturn($mock);
        $request = new Request();
        $request->setMethod('user');
        $request->setMethod("DELETE");
        $authController = new AuthController();
        $response = $authController->revoke($request);
        $this->assertEquals($response->status(), 200);
    }
}
