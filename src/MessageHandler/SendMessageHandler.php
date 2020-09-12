<?php

namespace App\MessageHandler;

use App\Message\SendMessage;
use App\Service\TwilioService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SendMessageHandler implements MessageHandlerInterface
{
    /**
     * @var TwilioService
     */
    private $twiloService;

    /**
     * MessageService constructor.
     *
     * @param TwilioService $service
     */
    public function __construct(TwilioService $service)
    {
        $this->twiloService = $service;
    }

    public function __invoke(SendMessage $sendMessage)
    {
        $message = $sendMessage->getMessage();

        $this->twiloService->sendMessage($message);
    }
}