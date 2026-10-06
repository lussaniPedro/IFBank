<?php

namespace App\Mail;

use App\Models\SolicitacaoLimite;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LimiteAprovadoMail extends Mailable {
    use Queueable, SerializesModels;

    public function __construct(
        public SolicitacaoLimite $solicitacao
    ){}

    public function build(): static {
        return $this
            ->subject('IFBank - Solicitação de limite aprovada')
            ->view('emails.limite-aprovado');
    }
}