<?php

namespace Anddye\Mailer;

use DateTimeInterface;
use Swift_Attachment;
use Swift_Message;

class MessageBuilder implements MessageBuilderInterface
{
    private $_swiftMessage;

    public function __construct(Swift_Message $swiftMessage)
    {
        $this->_swiftMessage = $swiftMessage;
    }

    public function attachFile(string $path): self
    {
        $this->_swiftMessage->attach(Swift_Attachment::fromPath($path));

        return $this;
    }

    public function detachFile(string $path): self
    {
        $this->_swiftMessage->detach(Swift_Attachment::fromPath($path));

        return $this;
    }

    public function getSwiftMessage(): Swift_Message
    {
        return $this->_swiftMessage;
    }

    public function setBcc(string $address, string $name = ''): self
    {
        $this->_swiftMessage->setBcc($address, $name);

        return $this;
    }

    public function setBody($body): self
    {
        $this->_swiftMessage->setBody($body, 'text/html');

        return $this;
    }

    public function setCc(string $address, string $name = ''): self
    {
        $this->_swiftMessage->setCc($address, $name);

        return $this;
    }

    public function setDate(DateTimeInterface $dateTime): self
    {
        $this->_swiftMessage->setDate($dateTime);

        return $this;
    }

    public function setFrom(string $address, string $name = ''): self
    {
        $this->_swiftMessage->setFrom($address, $name);

        return $this;
    }

    public function setPriority(int $priority): self
    {
        $this->_swiftMessage->setPriority($priority);

        return $this;
    }

    public function setReplyTo(string $address, string $name = ''): self
    {
        $this->_swiftMessage->setReplyTo($address, $name);

        return $this;
    }

    public function setSubject(string $subject): self
    {
        $this->_swiftMessage->setSubject($subject);

        return $this;
    }

    public function setTo(string $address, string $name = ''): self
    {
        $this->_swiftMessage->setTo($address, $name);

        return $this;
    }
}
