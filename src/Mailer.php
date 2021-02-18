<?php

namespace Anddye\Mailer;

use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Slim\Views\Twig;
use Swift_Transport;

class Mailer
{
    protected $host = 'localhost';

    protected $port = 25;

    protected $username = '';

    protected $password = '';

    protected $from = [];

    protected $swiftMailer;

    protected $twig;

    protected $protocol = null;

    public function __construct(Twig $twig, array $settings = [])
    {
        // Parse the settings, update the mailer properties.
        foreach ($settings as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }

        $transport = new Swift_SmtpTransport($this->host, $this->port, $this->protocol);
        $transport->setUsername($this->username);
        $transport->setPassword($this->password);

        $this->swiftMailer = new Swift_Mailer($transport);
        $this->twig = $twig;
    }

    public function setDefaultFrom(string $address, string $name = ''): self
    {
        $this->from = compact('address', 'name');

        return $this;
    }

    public function sendMessage($view, array $data = [], callable $callback = null): int
    {
        if ($view instanceof MailableInterface) {
            return $view->sendMessage($this);
        }

        $message = new MessageBuilder(new Swift_Message());
        $message->setFrom($this->from['address'], $this->from['name']);

        if ($callback) {
            call_user_func($callback, $message);
        }

        $message->setBody($this->twig->fetch($view, $data));

        return $this->swiftMailer->send($message->getSwiftMessage());
    }

    public function setTo(string $address, string $name = ''): PendingMailable
    {
        return (new PendingMailable($this))->setTo($address, $name);
    }

    public function getTransport(): Swift_Transport
    {
        return $this->swiftMailer->getTransport();
    }
}
