<?php

namespace App\Traits;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

trait ResetsPasswords
{
    use RedirectsUsers;
    
    
    /**
    * Reset the given user's password.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
    */
    public function reset(Request $request)
    {
        $request->validate($this->rules(), $this->validationErrorMessages());
        
        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $response = $this->broker()->reset(
        $this->credentials($request), function ($user, $password) {
            $this->resetPassword($user, $password);
        }
        );
        
        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $status == Password::RESET_LINK_SENT
        ? response()->json(['message' => 'Password Reseted ', 'status' => true], 201)
        : response()->json(['message' => 'Unable to reset password', 'status' => false], 401);
    }
    
    /**
    * Get the password reset validation rules.
    *
    * @return array
    */
    protected function rules()
    {
        return [
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|confirmed|min:8',
        ];
    }
    
    /**
    * Get the password reset validation error messages.
    *
    * @return array
    */
    protected function validationErrorMessages()
    {
        return [];
    }
    
    /**
    * Get the password reset credentials from the request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return array
    */
    protected function credentials(Request $request)
    {
        return $request->only(
        'email', 'password', 'password_confirmation', 'token'
        );
    }
    
    /**
    * Reset the given user's password.
    *
    * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
    * @param  string  $password
    * @return void
    */
    protected function resetPassword($user, $password)
    {
        $this->setUserPassword($user, $password);
        
        $user->setRememberToken(Str::random(60));
        
        $user->save();
        
        event(new PasswordReset($user));
        
        $this->guard()->login($user);
    }
    
    /**
    * Set the user's password.
    *
    * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
    * @param  string  $password
    * @return void
    */
    protected function setUserPassword($user, $password)
    {
        $user->password = Hash::make($password);
    }
    
    /**
    * Get the response for a successful password reset.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  string  $response
    * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
    */
    protected function sendResetResponse(Request $request, $response)
    {
        return response()->json(['status' => trans($response)]);
    }
    
    /**
    * Get the response for a failed password reset.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  string  $response
    * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
    */
    protected function sendResetFailedResponse(Request $request, $response)
    {
        return response()->json(['status' => trans($response)]);
    }
    
    /**
    * Get the broker to be used during password reset.
    *
    * @return \Illuminate\Contracts\Auth\PasswordBroker
    */
    public function broker()
    {
        return Password::broker();
    }
    
    /**
    * Get the guard to be used during password reset.
    *
    * @return \Illuminate\Contracts\Auth\StatefulGuard
    */
    protected function guard()
    {
        return Auth::guard();
    }
}