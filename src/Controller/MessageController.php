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
        // Create the form
        $form = $this->createFormBuilder()
                     ->add('task', TextType::class)
                     ->add('dueDate', DateType::class)
                     ->add('save', SubmitType::class, ['label' => 'Create Task'])
                     ->getForm();

        // on submit Validate the data, send the job
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $task = $form->getData();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            // $entityManager = $this->getDoctrine()->getManager();
            // $entityManager->persist($task);
            // $entityManager->flush();

            return $this->redirectToRoute('task_success');
        }

        // render the form
        return $this->render('base.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}