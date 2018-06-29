<?php

namespace Anddye\Interfaces;

use Anddye\Mailer\Mailer;

/**
 * Interface MailableInterface.
 *
 * @author Andrew Dyer <andrewdyer@outlook.com>
 *
 * @category Interfaces
 *
 * @see https://github.com/andrewdyer/slim3-mailer
 */
interface MailableInterface
{
    public function build();

    /**
     * @param Mailer $mailer
     */
    public function sendMessage(Mailer $mailer);
}
