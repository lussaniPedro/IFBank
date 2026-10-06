<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ClienteCriadoMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $password
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Credenciais de acesso - IFBank');
    }

    public function content(): Content
    {
        return new Content(view: 'let filtrados = $derived(
            clientes.filter(c =>
                `${c.user?.name ?? \'\'} ${c.user?.email ?? \'\'} ${c.conta?.numero_conta ?? \'\'}`
                .toLowerCase()
                .includes(filtro.toLowerCase())
            ));'
        );
    }
}
