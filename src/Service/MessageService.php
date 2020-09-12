<?php

namespace App\Service;

use App\Entity\Message;
use App\Message\SendMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\Security;

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
     * @var string|\Stringable|\Symfony\Component\Security\Core\User\UserInterface
     */
    private $user;

    /**
     * @var
     */
    private $security;

    /**
     * MessageService constructor.
     *
     * @param MessageBusInterface $bus
     * @param EntityManagerInterface $entityManager
     * @param Security $security
     */
    public function __construct(
        MessageBusInterface $bus,
        EntityManagerInterface $entityManager,
        Security $security
    )
    {
        $this->bus = $bus;
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    /**
     * Save the message information to the database, and trigger queuing the
     * message to be sent.
     *
     * @param Message $message
     * @param int $userId
     */
    public function saveMessage(Message $message)
    {
        $user = $this->security->getUser();
        $message->setUser($user);
        $message->setStatus(TwilioService::MESSAGE_STATUSES['PREPARING']);

        $this->entityManager->persist($message);
        $this->entityManager->flush();

        $this->queueMessage($message);
    }

    /**
     * Update the message status. The POST data from the request must contain
     * the message SmsSid to verify the external source calling the webhook
     * knows about the message SmsSid for security.
     *
     * @param int $id
     * @param string $status
     * @param string $smsSid
     * @return bool
     */
    public function updateMessageStatus(
        int $id
        , string $status,
        string $smsSid
    ): bool
    {
        $message = $this->entityManager->getRepository(Message::class)->find($id);

        if ($smsSid == $message->getSmsSid()) {
            $message->setStatus(TwilioService::MESSAGE_STATUSES[strtoupper($status)]);
            $this->entityManager->flush();

            return true;
        }

        return false;
    }

    /**
     * Place a given message on the Queue
     *
     * @param Message $message
     */
    private function queueMessage(Message $message)
    {
        $this->bus->dispatch(new SendMessage($message));
    }

    /**
     * Get all the messages
     *
     * @return mixed
     */
    public function getAllMessages()
    {
        return $this->entityManager->getRepository(Message::class)->findAll();
    }

    /**
     * Update a message smsSid, from the initial response from Twilio.
     *
     * @param Message $message
     * @param string $smsSid
     */
    public function updateMessageSid(Message $message, string $smsSid)
    {
        $message = $this->entityManager->getRepository(Message::class)->find($message->getId());
        $message->setSmsSid($smsSid);
        $this->entityManager->flush();
    }
}