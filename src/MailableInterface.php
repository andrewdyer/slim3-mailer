<?php

namespace Anddye\Mailer;

interface MailableInterface
{
    public function build();

    public function sendMessage(Mailer $mailer);
}
