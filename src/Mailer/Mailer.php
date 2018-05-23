<?php

namespace Anddye\Mailer;

use Anddye\Interfaces\MailableInterface;
use Swift_Mailer;
use Swift_Message;
use Slim\Views\Twig;

/**
 * Class Mailer
 * 
 * @author Andrew Dyer <andrewdyer@outlook.com>
 * @category Mailer
 * @see https://github.com/andrewdyer/slim3-mailer
 */
class Mailer
{

    /** @var string */
    private $_from = [];

    /** @var Swift_Mailer */
    private $_swiftMailer;

    /** @var Twig */
    private $_twig;

    /**
     * 
     * @param Swift_Mailer $swiftMailer
     * @param Twig $twig
     */
    public function __construct(Swift_Mailer $swiftMailer, Twig $twig)
    {
        $this->_swiftMailer = $swiftMailer;
        $this->_twig = $twig;
    }

    /**
     * 
     * @param string $address
     * @param string $name optional
     * @return $this
     */
    public function setDefaultFrom(string $address, string $name = "")
    {
        $this->_from = compact("address", "name");

        return $this;
    }

    /**
     * 
     * @param mixed $view
     * @param array $data optional
     * @param callable $callback optional
     * @return int
     */
    public function sendMessage($view, array $data = [], Callable $callback = null)
    {
        if ($view instanceof MailableInterface) {
            return $view->sendMessage($this);
        }

        $message = new MessageBuilder(new Swift_Message);
        $message->setFrom($this->_from["address"], $this->_from["name"]);

        if ($callback) {
            call_user_func($callback, $message);
        }

        $message->setBody($this->_twig->fetch($view, $data));

        return $this->_swiftMailer->send($message->getSwiftMessage());
    }

    /**
     * 
     * @param string $address
     * @param string $name optional
     * @return PendingMailable
     */
    public function setTo(string $address, string $name = "")
    {
        return (new PendingMailable($this))->setTo($address, $name);
    }

}
