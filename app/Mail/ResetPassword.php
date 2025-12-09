<?php

namespace App\Mail;

use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    public string $urlFrontend;
    private int $expiration = 60;
    public string $timeLimit;
    /**
     * Create a new message instance.
     */
    public function __construct(public $token, public $email, public $frontendRoute = '/auth/reset-password')
    {
        $this->urlFrontend = env('FRONTEND_URL') . $frontendRoute;
        
        //Calculate timeLimit
        $actualHour = Carbon::now();
        Log::info($actualHour->timezone);
        $this->timeLimit  = $actualHour->clone()->addMinutes($this->expiration)->format('h:i');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reset Password',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'notifications.reset-password',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
