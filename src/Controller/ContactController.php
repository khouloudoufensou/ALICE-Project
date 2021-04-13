<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Service\EmailService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
   
    // public function index(Request $request, EmailService $emailService): Response
    // {

    // }

     /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request, EmailService $emailService): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact->setSentAt(new DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();


            $sentToAdmin = $emailService->send([
                'replyTo' => $contact->getEmail(),
                'subject' => '[CONTACT] - ' . $contact->getSujet(),
                'template' => 'email/contact1.html.twig',
                'context' => [ 'contact' => $contact],
            ]);

            // Accusé de réception
            $sentToContact = $emailService->send([
                'to' => $contact->getEmail(),
                'subject' => "Merci de nous avoir contacté.",
                'template' => 'email/contact_confirmation.html.twig',
                'context' => [ 'contact' => $contact ],
            ]);

            if ($sentToAdmin && $sentToContact) {
                $this->addFlash('success', "Merci de nous avoir contacté");
                return $this->redirectToRoute('contact');
            } else {
                $this->addFlash('danger',"Une erreur est survenue pendant l'envoi d'email");
            }
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
            'contact'=> $contact,
        ]);
    }
}
    

