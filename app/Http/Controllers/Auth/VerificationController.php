<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }


    public function verify(Request $request)
    {
        $user = User::find($request->route('id'));

        // Check if the user exists
        if (!$user) {
            throw new AuthorizationException('User not found.');
        }

        // Check if the email is already verified
        if ($user->hasVerifiedEmail()) {
            // Redirect to a page indicating the email is already verified
            return redirect($this->redirectTo)->with('message', 'Your email has already been verified.');
        }

        // Validate the hash
        if (! hash_equals((string) $request->route('id'), (string) $user->getKey())) {
            throw new AuthorizationException('Invalid verification link.');
        }

        if (! hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            throw new AuthorizationException('Invalid verification link.');
        }

        // Mark the email as verified and log the user in
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));

            // Log the user in after verification
            Auth::login($user);
        }

        return redirect($this->redirectTo)->with('message', 'Email verified successfully. You are now logged in.');
    }
}
