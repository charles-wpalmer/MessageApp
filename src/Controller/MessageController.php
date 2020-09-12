<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Service\MessageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class MessageController extends AbstractController
{
    /**
     * @var MessageService
     */
    private $messageService;

    /**
     * @var AdapterInterface
     */
    private $cache;

    /**
     * MessageController constructor.
     *
     * @param MessageService $servcice
     * @param AdapterInterface $cache
     */
    public function __construct(MessageService $servcice, AdapterInterface $cache)
    {
        $this->messageService = $servcice;
        $this->cache = $cache;
    }

    /**
     * Handles the send message form
     *
     * @param Request $request
     * @return Response
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function send(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $message = new Message();
        $form = $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if (!$this->checkRatelimit()) {
            return $this->render('send.html.twig', [
                'rate_limit' => true,
                'form' => $form->createView(),
            ]);
        } else if ($form->isSubmitted() && $form->isValid()) {
            $message = $form->getData();
            $this->messageService->saveMessage($message);

            return $this->redirectToRoute('show_messages');
        }

        return $this->render('send.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Rate limit the user to one send message every 15 seconds
     *
     * @return bool
     * @throws \Psr\Cache\InvalidArgumentException
     */
    private function checkRateLimit()
    {
        $lock = $this->cache->getItem('send_message_lock_' . $user = $this->getUser()->getId());

        if (!$lock->isHit()) {
            $lock->set(true);
            $lock->expiresAfter(15);
            $this->cache->save($lock);

            return true;
        }

        return false;
    }

    /**
     * Update the status of the message - webhook from Twilio.
     *
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function updateStatus($id, Request $request)
    {
        $status = $request->get('MessageStatus');
        $smsSid = $request->get('SmsSid');

        $updated = $this->messageService->updateMessageStatus($id, $status, $smsSid);

        if ($updated) {
            $response = new Response();
            $response->headers->set('X-Status-Code', 200);
        } else {
            $response = new Response('Bad SmsSid!');
            $response->headers->set('X-Status-Code', 400);
        }

        return $response;
    }

    /**
     * Display all of the messages
     *
     * @return Response
     */
    public function show()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $messages = $this->messageService->getAllMessages();

        return $this->render('show.html.twig', [
            'messages' => $messages,
        ]);
    }
}