<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

/**
 * @group User management
 *
 * APIs for managing users
 */
class AuthController extends Controller
{
    /**
     * Registration .
     * This endpoint lets you create new user
     * @queryParam name required User name
     * @queryParam email required User email
     * @queryParam password required User password
     * @param  \Illuminate\Http\Request  $request
     *
     */
    public function register(Request $request){
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);

        $token = $user->createToken('apptoken')->plainTextToken;
        $user->remember_token = $token;
        $user->save();

        $response = [
          'user' => $user,
          'token' => $token
        ];

        return response($response, 201);
    }
    /**
     * Log out .
     *
     * This endpoint lets you log out
     * @authenticated
     * @param  \Illuminate\Http\Request  $request
     *
     * @response
     * {
     * 'User logged out'
     * }
     */
    public function logout(Request $request){
        auth()->user()->tokens()->delete();

        return  \response('User logged out', 200);
    }

    /**
     * Log in .
     *
     * This endpoint lets you login with credentials
     * @queryParam email required User email
     * @queryParam password required User password
     * @param  \Illuminate\Http\Request  $request
     *
     */
    public function login(Request $request){
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        // CHeck email
        $user = User::where('email', $fields['email'])->first();

        //CHeck password
        if(!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Bad credentials'
            ], 401);
        }

        $token = $user->createToken('apptoken')->plainTextToken;
        $user->remember_token = $token;
        $user->save();
        $response = [
            'user' => $user,
            'token' => $token
        ];


        return response($response, 201);
    }
}
