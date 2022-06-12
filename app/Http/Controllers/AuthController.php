<?php

namespace App\Http\Controllers;

use App\Models\UserPigeonProfile;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'name' => 'required'
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json($user);
    }

    public function registerPigeonProfile(Request $request)
    {
        $request->validate([
            'speed' => 'required|integer',
            'range' => 'required|integer',
            'cost' => 'required|integer',
            'downtime' => 'required|integer',
        ]);
        $user = $request->user();
        if ($user->pigeonProfile) {
            return response()->json(['message' => 'User already registered']);
        }
        $pigeonProfile = new UserPigeonProfile();
        $pigeonProfile->speed = $request->speed;
        $pigeonProfile->range = $request->range;
        $pigeonProfile->cost = $request->cost;
        $pigeonProfile->downtime = $request->downtime;
        $user->assignRole('pigeon');
        $user->pigeonProfile()->save($pigeonProfile);
        return response()->json($user->load('pigeonProfile'));
    }

    public function createToken(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);
        $user = User::where('email', $request->email)->first();
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(["Message" =>"The provided credentials are incorrect"], 401);
        }
        return response()->json(["token" =>$user->createToken($request->device_name)->plainTextToken]);
    }

    public function revoke(Request $request)
    {
        Auth::user()->tokens()->delete();
        return response()->json(['Message' => "Success"]);
    }
}
