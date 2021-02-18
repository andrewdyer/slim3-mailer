<?php

namespace Anddye\Mailer;

class PendingMailable
{
    private $mailer;
    private $to = [];

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendMessage(Mailable $mailable): int
    {
        $mailable->setTo($this->to['address'], $this->to['name']);

        return $this->mailer->sendMessage($mailable);
    }

    public function setTo(string $address, string $name = ''): self
    {
        $this->to = compact('address', 'name');

        return $this;
    }
}
