<?php

namespace Anddye\Mailer;

use DateTimeInterface;
use Swift_Attachment;
use Swift_Message;

class MessageBuilder implements MessageBuilderInterface
{
    private $swiftMessage;

    public function __construct(Swift_Message $swiftMessage)
    {
        $this->swiftMessage = $swiftMessage;
    }

    public function attachFile(string $path): self
    {
        $this->swiftMessage->attach(Swift_Attachment::fromPath($path));

        return $this;
    }

    public function detachFile(string $path): self
    {
        $this->swiftMessage->detach(Swift_Attachment::fromPath($path));

        return $this;
    }

    public function getSwiftMessage(): Swift_Message
    {
        return $this->swiftMessage;
    }

    public function setBcc(string $address, string $name = ''): self
    {
        $this->swiftMessage->setBcc($address, $name);

        return $this;
    }

    public function addBcc(string $address, string $name = '')
    {
        $this->swiftMessage->addBcc($address, $name);

        return $this;
    }

    public function setBody($body): self
    {
        $this->swiftMessage->setBody($body, 'text/html');

        return $this;
    }

    public function setCc(string $address, string $name = ''): self
    {
        $this->swiftMessage->setCc($address, $name);

        return $this;
    }

    public function addCc(string $address, string $name = '')
    {
        $this->swiftMessage->addCc($address, $name);

        return $this;
    }

    public function setDate(DateTimeInterface $dateTime): self
    {
        $this->swiftMessage->setDate($dateTime);

        return $this;
    }

    public function setFrom(string $address, string $name = ''): self
    {
        $this->swiftMessage->setFrom($address, $name);

        return $this;
    }

    public function setPriority(int $priority): self
    {
        $this->swiftMessage->setPriority($priority);

        return $this;
    }

    public function setReplyTo(string $address, string $name = ''): self
    {
        $this->swiftMessage->setReplyTo($address, $name);

        return $this;
    }

    public function setSubject(string $subject): self
    {
        $this->swiftMessage->setSubject($subject);

        return $this;
    }

    public function setTo(string $address, string $name = ''): self
    {
        $this->swiftMessage->setTo($address, $name);

        return $this;
    }
}
