<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Services\AlertInterface;
use App\Services\MailerServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class ContactController extends AbstractController
{
    public function __construct(
        private AlertInterface          $alert,
        private EntityManagerInterface  $em,
        private MailerServiceInterface  $mailer,
    )
    {
    }

    #[Route('/contact', name: 'contact')]
    public function index(Request $request): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($contact);
            $this->em->flush();

            //send mail
            $formForget = $this->getParameter('email_noreply');
            $to = $this->getParameter('email');
            $textTemplate = 'contact/email/contact.txt.twig';
            $htmlTemplate = 'contact/email/contact.html.twig';
            $params = [
                'contact' => $contact,
            ];
            $this->mailer->send($formForget, $to, $textTemplate, $htmlTemplate, $params);

            $this->alert->success('Votre email à bien été envoyé');
            return $this->redirectToRoute('contact');
        }

        return $this->render('contact/index.html.twig', [
            'contact' => $contact,
            'form' => $form->createView()
        ]);
    }
}
