<?php

namespace Anddye\Mailer\Tests;

use Anddye\Mailer\Mailer;
use Anddye\Mailer\MessageBuilder;
use PHPUnit\Framework\TestCase;
use Slim\Views\Twig;

final class MailerTest extends TestCase
{
    public function testCanConfigureTransport(): void
    {
        $host = getenv('MAILER_HOST');
        $port = getenv('MAILER_PORT');
        $protocol = getenv('MAILER_ENCRYPTION');
        $username = getenv('MAILER_USERNAME');
        $password = getenv('MAILER_PASSWORD');

        $mailer = new Mailer(new Twig(__DIR__), compact('host', 'port', 'username', 'password', 'protocol'));

        $transport = $mailer->getTransport();

        $this->assertEquals($host, $transport->getHost());
        $this->assertEquals($port, $transport->getPort());
        $this->assertEquals($protocol, $transport->getEncryption());
        $this->assertEquals($username, $transport->getUsername());
        $this->assertEquals($password, $transport->getPassword());
    }

    public function testCanSendMessage(): void
    {
        $address = getenv('MAILER_DEFAULT_FROM_ADDRESS');
        $name = getenv('MAILER_DEFAULT_FROM_NAME');

        $mailer = new Mailer(new Twig(__DIR__), [
            'host' => getenv('MAILER_HOST'),
            'port' => getenv('MAILER_PORT'),
            'username' => getenv('MAILER_USERNAME'),
            'password' => getenv('MAILER_PASSWORD'),
            'protocol' => getenv('MAILER_ENCRYPTION'),
        ]);

        $mailer->setDefaultFrom($address, $name);

        $sent = $mailer->sendMessage('views/mailer.twig', [], function (MessageBuilder $messageBuilder) use ($address, $name) {
            $messageBuilder->setTo($address, $name);
            $messageBuilder->setSubject('Welcome to the Team!');
        });

        $this->assertEquals(1, $sent);
    }
}
