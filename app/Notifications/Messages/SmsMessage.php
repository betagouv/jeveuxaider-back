<?php

namespace App\Notifications\Messages;

use App\Services\Sendinblue;
use Illuminate\Support\Facades\Log;

class SmsMessage
{
    protected string $to;
    protected string $from;
    protected array $lines;
    protected string $tag;
    protected string $reroute;

    /**
     * SmsMessage constructor.
     * @param array $lines
     */
    public function __construct($lines = [], $tag = '', $from = 'JeVeuxAider')
    {
        $this->lines = $lines;
        $this->tag = $tag;
        $this->from = $from;
        $this->reroute = config('services.sms.reroute');
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
        if (!$this->from || mb_strlen($this->from) > 15 || !$this->to || !count($this->lines)) {
            throw new \Exception('SMS is not correct.');
        }

        $message = implode("\n", $this->lines);

        if(config('services.sms.enable') === false) {
            return Log::info('SMS are disabled', [
                'from' => $this->from,
                'to' => $this->to,
                'message' => $message,
                'tag' => $this->tag,
                'reroute' => $this->reroute,
            ]);
        }

        return Sendinblue::sendSmsMessage(
            $this->from,
            $this->reroute ?? $this->to,
            $message,
            $this->tag
        );
    }
}
