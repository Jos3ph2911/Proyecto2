<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ActivarCuentaMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;

    /**
     * Recibimos el usuario al que se le enviará el correo.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Asunto del correo.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Activa tu cuenta en Aventones',
        );
    }

    /**
     * Vista y datos que se envían al correo.
     */
    public function content(): Content
    {
        $url = route('activar.cuenta', $this->user->token_activacion);

        return new Content(
            view: 'emails.activar-cuenta',
            with: [
                'user' => $this->user,
                'url'  => $url,
            ],
        );
    }


    public function attachments(): array
    {
        return [];
    }
}
