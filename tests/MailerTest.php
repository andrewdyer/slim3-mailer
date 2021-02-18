<?php

namespace Anddye\Mailer\Tests;

use Anddye\Mailer\Mailer;
use PHPUnit\Framework\TestCase;
use Slim\Views\Twig;

final class MailerTest extends TestCase
{
    public function testCanConfigureTransport(): void
    {
        $host = 'localhost';
        $port = 25;
        $encryption = 'tls';
        $username = 'username';
        $password = 'password';

        $mailer = new Mailer(new Twig(__DIR__), compact('host', 'port', 'username', 'password', 'encryption'));

        $transport = $mailer->getTransport();

        $this->assertEquals($host, $transport->getHost());
        $this->assertEquals($port, $transport->getPort());
        $this->assertEquals($encryption, $transport->getEncryption());
        $this->assertEquals($username, $transport->getUsername());
        $this->assertEquals($password, $transport->getPassword());
    }
}
