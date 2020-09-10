<?php

namespace App\Service;

use App\Entity\Message;
use App\Message\SendMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class MessageService
{
    /**
     * @var MessageBusInterface
     */
    private $bus;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * MessageService constructor.
     *
     * @param MessageBusInterface $bus
     */
    public function __construct(MessageBusInterface $bus, EntityManagerInterface $entityManager)
    {
        $this->bus = $bus;
        $this->entityManager = $entityManager;
    }

    /**
     * Save the message information to the database, and trigger queuing the
     * message to be sent.
     *
     * @param Message $message
     */
    public function saveMessage(Message $message)
    {
        $message->setStatus(TwiloService::MESSAGE_STATUSES['QUEUED']);

        $this->entityManager->persist($message);
        $this->entityManager->flush();

        $this->queueMessage($message);
    }

    private function queueMessage(Message $message)
    {
        // Queue Message on RabbitMQ
        $this->bus->dispatch(new SendMessage($message));
    }
}