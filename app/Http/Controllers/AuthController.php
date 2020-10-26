<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class AuthController extends Controller
{
    /**
    * Store a new user.
    *
    * @param  Request  $request
    * @return Response
    */
    public function register(Request $request)
    {
        //validate incoming request
        $this->validate($request, [
        'username' => 'required|string',
        'email' => 'required|email|unique:users',
        'password' => 'required|confirmed',
        ]);
        
        try {
            
            $user = new User;
            $user->username = $request->input('username');
            $user->email = $request->input('email');
            $plainPassword = $request->input('password');
            $user->password = app('hash')->make($plainPassword);
            $user->isActive = 0;
            
            $user->save();
            
            //return successful response
            return response()->json(['user' => $user, 'message' => 'CREATED'], 201);
            
        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => $e], 409);
        }
        
    }
    
    
    /**
    * Get a JWT via given credentials.
    *
    * @param  Request  $request
    * @return Response
    */
    public function login(Request $request)
    {
        //validate incoming request
        $this->validate($request, [
        'email' => 'required|string',
        'password' => 'required|string',
        ]);
        
        $credentials = $request->only(['email', 'password']);
        
        if (! $token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        
        return $this->respondWithToken($token);
    }
    
    /**
    * Logout
    *
    * @param  Request  $request
    * @return Response
    */
    public function logout(Request $request)
    {
        Auth::logout();
        return response()->json(['message' => 'Successfully logged out'], 200);
    }
    
    /**
    * Get user details.
    *
    * @param  Request  $request
    * @return Response
    */
    public function me()
    {
        return response()->json(auth()->user());
    }
    
    
    
    
}