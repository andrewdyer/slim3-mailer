<?php

namespace Anddye\Mailer;

use DateTimeInterface;
use Swift_Attachment;
use Swift_Message;

/**
 * Class MessageBuilder.
 *
 * @author Andrew Dyer <andrewdyer@outlook.com>
 *
 * @category Mailer
 *
 * @see https://github.com/andrewdyer/slim3-mailer
 */
class MessageBuilder implements MessageBuilderInterface
{
    /** @var Swift_Message */
    private $_swiftMessage;

    /**
     * @param Swift_Message $swiftMessage
     */
    public function __construct(Swift_Message $swiftMessage)
    {
        $this->_swiftMessage = $swiftMessage;
    }

    /**
     * @param string $path
     */
    public function attachFile(string $path): self
    {
        $this->_swiftMessage->attach(Swift_Attachment::fromPath($path));

        return $this;
    }

    /**
     * @param string $path
     */
    public function detachFile(string $path): self
    {
        $this->_swiftMessage->detach(Swift_Attachment::fromPath($path));

        return $this;
    }

    /**
     * @return Swift_Message
     */
    public function getSwiftMessage(): Swift_Message
    {
        return $this->_swiftMessage;
    }

    /**
     * @param string $address
     * @param string $name    optional
     */
    public function setBcc(string $address, string $name = ''): self
    {
        $this->_swiftMessage->setBcc($address, $name);

        return $this;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body): self
    {
        $this->_swiftMessage->setBody($body, 'text/html');

        return $this;
    }

    /**
     * @param string $address
     * @param string $name    optional
     */
    public function setCc(string $address, string $name = ''): self
    {
        $this->_swiftMessage->setCc($address, $name);

        return $this;
    }

    /**
     * @param string $address
     * @param string $name optional
     */
    public function setReplyTo(string $address, string $name = ''): self
    {
        $this->_swiftMessage->setReplyTo($address, $name);

        return $this;
    }

    /**
     * @param DateTimeInterface $dateTime
     */
    public function setDate(DateTimeInterface $dateTime): self
    {
        $this->_swiftMessage->setDate($dateTime);

        return $this;
    }

    /**
     * @param string $address
     * @param string $name    optional
     */
    public function setFrom(string $address, string $name = ''): self
    {
        $this->_swiftMessage->setFrom($address, $name);

        return $this;
    }

    /**
     * @param int $priority
     */
    public function setPriority(int $priority): self
    {
        $this->_swiftMessage->setPriority($priority);

        return $this;
    }

    /**
     * @param string $subject
     */
    public function setSubject(string $subject): self
    {
        $this->_swiftMessage->setSubject($subject);

        return $this;
    }

    /**
     * @param type $address
     * @param type $name    optional
     */
    public function setTo(string $address, string $name = ''): self
    {
        $this->_swiftMessage->setTo($address, $name);

        return $this;
    }
}
