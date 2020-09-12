<?php

namespace App\Service;

use App\Entity\Message;
use Twilio\Rest\Client;

class TwiloService
{
    /**
     * @var array array of possible statuses for a message
     */
    const MESSAGE_STATUSES = [
        'PREPARING' => 0,
        'QUEUED' => 1,
        'SENT' => 2,
        'FAILED' => 3,
        'DELIVERED' => 4,
        'UNDELIVERED' => 5,
    ];

    /**
     * @var Client
     */
    private $twilio;

    public function __construct(Client $client)
    {
        $this->twilio = $client;
    }

    public function sendMessage(Message $message)
    {
        var_dump($message->getMessage());

        $message = $this->twilio->messages->create($message->getPhoneNumber(),
                [
                    "body" => "McAvoy or Stewart? These timelines can get so confusing.",
                    "from" => "+12059464152",
                    //"statusCallback" => "http://postb.in/1234abcd"
                ]
            );

        print($message->sid);
        // Send Message via twilo
    }
}