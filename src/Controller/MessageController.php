<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;

class MessageController extends AbstractController
{
    public function send(): Response
    {
        $form = $this->createFormBuilder()
                     ->add('task', TextType::class)
                     ->add('dueDate', DateType::class)
                     ->add('save', SubmitType::class, ['label' => 'Create Task'])
                     ->getForm();

        return $this->render('base.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}