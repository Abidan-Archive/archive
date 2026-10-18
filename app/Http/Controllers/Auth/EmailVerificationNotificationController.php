<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\AppServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(AppServiceProvider::HOME);
        }

        try {
            $request->user()->sendEmailVerificationNotification();
        } catch (TransportExceptionInterface $exception) {
            report($exception);

            return back()->with('flash', [
                'type' => 'warn',
                'message' => 'We could not send your verification email. Please try again later.',
                'autohide' => false,
            ]);
        }

        return back()->with('flash', [
            'message' => 'A new verification link has been sent to the email address you provided during registration.',
            'autohide' => false,
        ]);
    }
}
