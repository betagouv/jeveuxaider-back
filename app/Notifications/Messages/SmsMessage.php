<?php

namespace App\Notifications\Messages;

use App\Services\Sendinblue;

class SmsMessage
{
    protected string $to;
    protected string $from;
    protected array $lines;
    protected string $tag;

    /**
     * SmsMessage constructor.
     * @param array $lines
     */
    public function __construct($lines = [], $tag = '', $from = 'JeVeuxAider')
    {
        $this->lines = $lines;
        $this->tag = $tag;
        $this->from = $from;
    }

    public function line($line = ''): self
    {
        $this->lines[] = $line;
        return $this;
    }

    public function to($to): self
    {
        $this->to = $to;
        return $this;
    }

    public function from($from): self
    {
        $this->from = $from;
        return $this;
    }

    public function send(): mixed
    {
        if (!$this->from || !$this->to || !count($this->lines)) {
            throw new \Exception('SMS is not correct.');
        }

        return Sendinblue::sendSmsMessage(
            $this->from,
            $this->to,
            implode("\n", $this->lines),
            $this->tag
        );
    }
}
