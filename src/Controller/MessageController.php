<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Service\MessageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MessageController extends AbstractController
{
    /**
     * @var MessageService
     */
    private $messageService;

    /**
     * MessageController constructor.
     *
     * @param MessageService $servcice
     */
    public function __construct(MessageService $servcice )
    {
        $this->messageService = $servcice;
    }

    public function send(Request $request): Response
    {
        $message = new Message();
        $form = $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message = $form->getData();
            $this->messageService->saveMessage($message);
        }

        return $this->render('base.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}