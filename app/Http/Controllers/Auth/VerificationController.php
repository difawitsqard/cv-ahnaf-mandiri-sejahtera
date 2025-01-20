<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
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
        $this->middleware('signed')->only('verify');
        // $this->middleware('throttle:1,1')->only('resend');
        $this->middleware('throttle:6,1')->only('verify');
    }

    /**
     * Handle caching logic for throttling requests.
     *
     * @param string $key
     * @param string|null $errorMessage
     * @return \Illuminate\Http\RedirectResponse|null
     */
    protected function checkThrottle($key, $errorMessage = null)
    {
        if (Cache::has($key)) {
            $errorMessage = $errorMessage ?? 'Too many attempts. Please try again later.';
            return back()->withErrors(['error' => $errorMessage]);
        }
        return null;
    }

    /**
     * Set cache key after successful operation.
     *
     * @param string $key
     * @param int $retryAfter
     * @return void
     */
    protected function setThrottle($key, $retryAfter)
    {
        Cache::put($key, true, $retryAfter);
    }

    public function show(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
            //  $this->redirectPath();
        }

        return view('auth.verify');
    }

    public function showChangeEmailForm(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        return view('auth.email.change');
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
        if (!hash_equals((string) $request->route('id'), (string) $user->getKey())) {
            throw new AuthorizationException('Invalid verification link.');
        }

        if (!hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            throw new AuthorizationException('Invalid verification link.');
        }

        // Mark the email as verified and log the user in
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
            Auth::login($user);
        }

        return redirect($this->redirectTo)->with('message', 'Email verified successfully. You are now logged in.');
    }

    public function changeEmail(Request $request)
    {
        $user = $request->user();
        $cacheKey = 'email-change-attempts-' . $user->id;
        $retryAfter = 300; // Set timeout to 5 minutes

        // Check throttle
        $throttleResponse = $this->checkThrottle($cacheKey, 'Terlalu sering mencoba mengganti email. Silakan coba lagi nanti.');
        if ($throttleResponse) {
            return $throttleResponse;
        }

        try {
            $validatedData = $request->validate([
                'email' => 'required|email|unique:users,email',
            ]);

            $user->email = $validatedData['email'];
            $user->email_verified_at = null;
            $user->save();

            $user->sendEmailVerificationNotification();

            // Set cache setelah berhasil
            $this->setThrottle($cacheKey, $retryAfter);

            return redirect(route('verification.notice'));
        } catch (Exception $e) {
            return back()->withErrors(['email' => $e->getMessage()]);
        }
    }

    public function resend(Request $request)
    {

        if ($request->user()->hasVerifiedEmail()) {
            return $request->wantsJson()
                ? new JsonResponse([], 204)
                : redirect($this->redirectPath());
        }

        $user = $request->user();
        $cacheKey = 'email-resend-attempts-' . $user->id;
        $retryAfter = 60; // Set timeout to 1 minute

        // Check throttle
        $throttleResponse = $this->checkThrottle($cacheKey, 'Terlalu sering mengirim ulang verifikasi email. Silakan coba lagi nanti.');
        if ($throttleResponse) {
            return $throttleResponse;
        }

        $request->user()->sendEmailVerificationNotification();

        // Set cache setelah berhasil
        $this->setThrottle($cacheKey, $retryAfter);

        return $request->wantsJson()
            ? new JsonResponse([], 202)
            : back()->with('resent', true);
    }
}
