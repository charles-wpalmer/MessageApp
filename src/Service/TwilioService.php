<?php

namespace App\Service;

use App\Entity\Message;
use Twilio\Rest\Client;

class TwilioService
{
    /**
     * @var array of possible statuses for a message
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
     * @var array Readable array of possible statuses for a message
     */
    const READABLE_MESSAGE_STATUSES = [
        0 => 'Preparing',
        1 => 'Queues',
        2 => 'Sent',
        3 => 'Failed',
        4 => 'Delivered',
        5 => 'Undelivered',
    ];

    /**
     * @var Client
     */
    private $twilio;

    /**
     * @var MessageService
     */
    private $messageService;

    /**
     * TwilioService constructor.
     *
     * @param Client $client
     * @param MessageService $messageService
     */
    public function __construct(Client $client, MessageService $messageService)
    {
        $this->twilio = $client;
        $this->messageService = $messageService;
    }

    /**
     * Send the message to Twilio
     *
     * @param Message $message
     * @throws \Twilio\Exceptions\TwilioException
     */
    public function sendMessage(Message $message)
    {
        $smsMessage = $this->twilio->messages->create(
            $message->getPhoneNumber(),
                [
                    "body" => $message->getMessage(),
                    "from" => "+447445049414",
                    "statusCallback" => "http://randomstring.ngrok.io/api/message/" . $message->getId()
                ]
        );

        $this->messageService->updateMessageSid($message, "TESTING 123");
    }
}