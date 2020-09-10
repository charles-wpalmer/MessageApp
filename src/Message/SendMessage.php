<?php

namespace App\Message;

use App\Entity\Message;

class SendMessage
{
    /**
     * @var Message
     */
    private $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get the message object
     *
     * @return Message
     */
    public function getMessage(): Message
    {
        return $this->message;
    }
}