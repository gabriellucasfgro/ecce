<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SolicitarAlteracaoEmail extends Mailable {

    use Queueable, SerializesModels;
    public $view;
    public $dados;
    public $titulo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($view, $dados, $titulo) {
        $this->view = $view;
        $this->dados = $dados;
        $this->titulo = $titulo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        return $this->view($this->view)
            ->from("ecce@ifpr.edu.br", "ECCE - Emissao e Controle de Carteirinhas Estudantis")
            // ->cc($address, $name)
            // ->bcc($address, $name)
            // ->replyTo($address, $name)
            ->subject($this->titulo)
            ->with('dados', $this->dados);
    }
}
