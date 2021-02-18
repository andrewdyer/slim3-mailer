<?php

namespace Anddye\Mailer;

class PendingMailable
{
    private $_mailer;

    private $_to = [];

    public function __construct(Mailer $mailer)
    {
        $this->_mailer = $mailer;
    }

    public function sendMessage(Mailable $mailable)
    {
        $mailable->setTo($this->_to['address'], $this->_to['name']);

        return $this->_mailer->sendMessage($mailable);
    }

    public function setTo(string $address, string $name = ''): self
    {
        $this->_to = compact('address', 'name');

        return $this;
    }
}
