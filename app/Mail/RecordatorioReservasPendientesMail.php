<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class RecordatorioReservasPendientesMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $chofer;

    /** @var \Illuminate\Support\Collection */
    public Collection $reservas;

    /**
     * @param \App\Models\User              $chofer
     * @param \Illuminate\Support\Collection $reservas
     */
    public function __construct(User $chofer, Collection $reservas)
    {
        $this->chofer   = $chofer;
        $this->reservas = $reservas;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Recordatorio: tenés reservas pendientes por gestionar',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.recordatorio-reservas-pendientes',
            with: [
                'chofer'   => $this->chofer,
                'reservas' => $this->reservas,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
