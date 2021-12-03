<?php

namespace Anddye\Mailer;

use DateTimeInterface;

interface MessageBuilderInterface
{
    public function attachFile(string $path);

    public function detachFile(string $path);

    public function setBcc(string $address, string $name = '');

    public function addBcc(string $address, string $name = '');

    public function setBody($body);

    public function setCc(string $address, string $name = '');

    public function addCc(string $address, string $name = '');

    public function setDate(DateTimeInterface $dateTime);

    public function setFrom(string $address, string $name = '');

    public function setPriority(int $priority);

    public function setReplyTo(string $address, string $name = '');

    public function setSubject(string $subject);

    public function setTo(string $address, string $name = '');
}
