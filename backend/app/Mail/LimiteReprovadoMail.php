<?php

namespace App\Mail;

use App\Models\SolicitacaoLimite;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LimiteReprovadoMail extends Mailable {
    use Queueable, SerializesModels;

    public function __construct(
        public SolicitacaoLimite $solicitacao
    ){}

    public function build(): static {
        return $this
            ->subject('IFBank - Solicitação de limite reprovada')
            ->view('emails.limite-reprovado');
    }
}