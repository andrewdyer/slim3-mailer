<?php

namespace Anddye\Mailer;

/**
 * Class PendingMailable.
 *
 * @author Andrew Dyer <andrewdyer@outlook.com>
 *
 * @category Mailer
 *
 * @see https://github.com/andrewdyer/slim3-mailer
 */
class PendingMailable
{
    /** @var Mailer */
    private $_mailer;

    /** @var array */
    private $_to = [];

    /**
     * @param Mailer $mailer
     */
    public function __construct(Mailer $mailer)
    {
        $this->_mailer = $mailer;
    }

    /**
     * @param Mailable $mailable
     *
     * @return mixed
     */
    public function sendMessage(Mailable $mailable)
    {
        $mailable->setTo($this->_to['address'], $this->_to['name']);

        return $this->_mailer->sendMessage($mailable);
    }

    /**
     * @param string $address
     * @param string $name    optional
     *
     * @return $this
     */
    public function setTo(string $address, string $name = '')
    {
        $this->_to = compact('address', 'name');

        return $this;
    }
}
