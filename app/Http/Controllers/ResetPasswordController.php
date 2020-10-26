<?php
namespace App\Http\Controllers;

use Illuminate\Auth\Passwords\PasswordBrokerManager;
use Illuminate\Support\Facades\Password;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller {
    // 1. Send reset password email
    public function generateResetToken(Request $request) {
        // Check email address is valid
        $this->validate($request, ['email' => 'required|email']);
        
        // Send password reset to the user with this email address
        $response = $this->broker()->sendResetLink( $request->only('email'));
        return $response == Password::RESET_LINK_SENT
        ? response()->json(['message' => 'Reset link sent to your email.', 'status' => true], 201)
        : response()->json(['message' => 'Unable to send reset link', 'status' => false], 401);
    }
    
    // 2. Reset Password
    public function resetPassword(Request $request) { // Check input is valid
        $rules = [ 'token' => 'required', 'username' => 'required|string',
        'password' => 'required|confirmed|min:6', ];
        $this->validate($request, $rules); // Reset the password
        $response = $this->broker()->reset( $this->credentials($request), function ($user, $password) { $user->password = app('hash')->make($password);
            $user->save();
        } ); return $response == Password::PASSWORD_RESET ? response()->json(true) : response()->json(false); }
        /** * Get the password reset credentials from the request. * * @param \Illuminate\Http\Request $request * @return array */
        protected function credentials(Request $request)
        {
        return $request->only('username', 'password', 'password_confirmation', 'token'); }
        /** * Get the broker to be used during password reset. * * @return \Illuminate\Contracts\Auth\PasswordBroker */
        public function broker() {
            $passwordBrokerManager = new PasswordBrokerManager(app());
        return $passwordBrokerManager->broker(); }
    }