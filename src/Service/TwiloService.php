<?php

namespace App\Service;

use App\Entity\Message;

class TwiloService
{
    /**
     * @var array array of possible statuses for a message
     */
    const MESSAGE_STATUSES = [
        'QUEUED' => 1,
        'SENT' => 1,
        'FAILED' => 1,
    ];

    public function sendMessage(Message $message)
    {
        var_dump($message->getMessage());
        // Send Message via twilo
    }
}