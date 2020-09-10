<?php

namespace App\MessageHandler;

use App\Message\SendMessage;
use App\Service\TwiloService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SendMessageHandler implements MessageHandlerInterface
{
    /**
     * @var TwiloService
     */
    private $twiloService;

    /**
     * MessageService constructor.
     *
     * @param TwiloService $service
     */
    public function __construct(TwiloService $service)
    {
        $this->twiloService = $service;
    }

    public function __invoke(SendMessage $sendMessage)
    {
        $message = $sendMessage->getMessage();

        $this->twiloService->sendMessage($message);
    }
}