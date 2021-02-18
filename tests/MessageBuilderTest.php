<?php

namespace Anddye\Mailer\Tests;

use Anddye\Mailer\MessageBuilder;
use PHPUnit\Framework\TestCase;
use Swift_Message;

final class MessageBuilderTest extends TestCase
{
    private $messageBuilder;
    private $swiftMessage;

    protected function setUp(): void
    {
        parent::setUp();

        $this->swiftMessage = new Swift_Message();
        $this->messageBuilder = new MessageBuilder($this->swiftMessage);
    }

    public function testCanSetBcc(): void
    {
        $address = 'andrewdyer@mail.com';
        $name = 'Andrew Dyer';

        $this->messageBuilder->setBcc($address, $name);

        $this->assertEquals([$address => $name], $this->swiftMessage->getBcc());
    }

    public function testCanSetBody(): void
    {
        $message = 'Hello world!';

        $this->messageBuilder->setBody($message);

        $this->assertEquals($message, $this->swiftMessage->getBody());
    }

    public function testCanSetCc(): void
    {
        $address = 'andrewdyer@mail.com';
        $name = 'Andrew Dyer';

        $this->messageBuilder->setCc($address, $name);

        $this->assertEquals([$address => $name], $this->swiftMessage->getCc());
    }

    public function testCanSetDate(): void
    {
        $date = new \DateTime('2021-02-18');

        $this->messageBuilder->setDate($date);

        $this->assertEquals($date, $this->swiftMessage->getDate());
    }

    public function testCanSetFrom(): void
    {
        $address = 'andrewdyer@mail.com';
        $name = 'Andrew Dyer';

        $this->messageBuilder->setFrom($address, $name);

        $this->assertEquals([$address => $name], $this->swiftMessage->getFrom());
    }

    public function testCanSetPriority(): void
    {
        $priority = 1;

        $this->messageBuilder->setPriority($priority);

        $this->assertEquals($priority, $this->swiftMessage->getPriority());
    }

    public function testCanSetReplyTo(): void
    {
        $address = 'andrewdyer@mail.com';
        $name = 'Andrew Dyer';

        $this->messageBuilder->setReplyTo($address, $name);

        $this->assertEquals([$address => $name], $this->swiftMessage->getReplyTo());
    }

    public function testCanSetSubject(): void
    {
        $subject = 'Welcome to the team!';

        $this->messageBuilder->setSubject($subject);

        $this->assertEquals($subject, $this->swiftMessage->getSubject());
    }

    public function testCanSetTo(): void
    {
        $address = 'andrewdyer@mail.com';
        $name = 'Andrew Dyer';

        $this->messageBuilder->setTo($address, $name);

        $this->assertEquals([$address => $name], $this->swiftMessage->getTo());
    }
}
