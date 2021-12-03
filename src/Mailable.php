<?php

namespace Anddye\Mailer;

use DateTimeInterface;

abstract class Mailable implements MailableInterface, MessageBuilderInterface
{
    protected $attachments = [];
    protected $bcc = [];
    protected $bccs = [];
    protected $cc = [];
    protected $ccs = [];
    protected $data = [];
    protected $dateTime;
    protected $from = [];
    protected $priority;
    protected $replyTo = [];
    protected $subject;
    protected $to = [];
    protected $view;

    public function attachFile(string $path): self
    {
        $this->attachments[] = $path;

        return $this;
    }

    public function detachFile(string $path): self
    {
        if (false !== ($key = array_search($path, $this->attachments))) {
            unset($this->attachments[$key]);
        }

        return $this;
    }

    public function sendMessage(Mailer $mailer): int
    {
        $this->build();

        return $mailer->sendMessage($this->view, $this->data, function ($message) {
            $message->setTo($this->to['address'], $this->to['name']);
            $message->setSubject($this->subject);

            if ($this->from) {
                $message->setFrom($this->from['address'], $this->from['name']);
            }

            if ($this->bcc) {
                $message->setBcc($this->bcc['address'], $this->bcc['name']);
            }

            if ($this->bccs) {
                foreach ($this->bccs as $bcc) {
                    $message->addBcc($bcc['address'], $bcc['name']);
                }
            }

            if ($this->cc) {
                $message->setCc($this->cc['address'], $this->cc['name']);
            }

            if ($this->ccs) {
                foreach ($this->ccs as $cc) {
                    $message->addCc($cc['address'], $cc['name']);
                }
            }

            if ($this->replyTo) {
                $message->setReplyTo($this->replyTo['address'], $this->replyTo['name']);
            }

            if ($this->priority) {
                $message->setPriority($this->priority);
            }

            foreach ($this->attachments as $path) {
                $message->attachFile($path);
            }
        });
    }

    public function setBcc(string $address, string $name = ''): self
    {
        $this->bcc = compact('address', 'name');

        return $this;
    }

    public function addBcc(string $address, string $name = '')
    {
        if (!$this->bccs) {
            $this->bccs = [];
        }
        array_push($this->bccs, [
            "address" => $address,
            "name" => $name
        ]);

        return $this;
    }

    public function setBody($body): self
    {
        // TODO: Merge this and set view together

        return $this;
    }

    public function setCc(string $address, string $name = ''): self
    {
        $this->cc = compact('address', 'name');

        return $this;
    }

    public function addCc(string $address, string $name = '')
    {
        if (!$this->ccs) {
            $this->ccs = [];
        }
        array_push($this->ccs, [
            "address" => $address,
            "name" => $name
        ]);

        return $this;
    }

    public function setDate(DateTimeInterface $dateTime): self
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    public function setFrom(string $address, string $name = ''): self
    {
        $this->from = compact('address', 'name');

        return $this;
    }

    public function setPriority(int $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    public function setReplyTo(string $address, string $name = ''): self
    {
        $this->replyTo = compact('address', 'name');

        return $this;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function setTo(string $address, string $name = ''): self
    {
        $this->to = compact('address', 'name');

        return $this;
    }

    public function setView(string $view, array $data = []): self
    {
        $this->view = $view;
        $this->data = $data;

        return $this;
    }
}
