<?php

namespace App\Message;

class MailMessage
{
    private string $subject;
    private string $receiver;
    private string $template;
    private array $context;

    public function __construct(string $subject, string $receiver, string $template, array $context)
    {
        $this->subject = $subject;
        $this->receiver = $receiver;
        $this->template = $template;
        $this->context = $context;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getReceiver(): string
    {
        return $this->receiver;
    }

    public function setReceiver(string $receiver): self
    {
        $this->receiver = $receiver;

        return $this;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function setTemplate(string $template): self
    {
        $this->template = $template;

        return $this;
    }

    public function getContext(): array
    {
        return $this->context;
    }
    public function addContext(string $key, $value): self
    {
        $this->context[$key] = $value;

        return $this;
    }

    public function removeContext(string $key): self
    {
        if (array_key_exists($key, $this->context)) {
            unset($this->context[$key]);
        }

        return $this;
    }
}
