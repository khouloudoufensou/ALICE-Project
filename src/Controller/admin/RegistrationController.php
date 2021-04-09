<?php

namespace App\Controller\admin;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\LoginFormAuthenticator;
use App\Service\EmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Nzo\UrlEncryptorBundle\Encryptor\Encryptor;

class RegistrationController extends AbstractController
{
    // /**
    //  * @Route("/register", name="app_register")
    //  */
    // public function register(Request $request, 
    // UserPasswordEncoderInterface $passwordEncoder, 
    // GuardAuthenticatorHandler $guardHandler, 
    // LoginFormAuthenticator $authenticator,
    // EmailService $emailService
    // ): Response
    // {
    //     if ($this->getUser()) {
    //         return $this->redirectToRoute('redirect_user');
    //     }

    //     $user = new User();
    //     $form = $this->createForm(RegistrationFormType::class, $user);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $user->setRoles(["ROLE_MEMBER"]);
    //         // encode the plain password
    //         $user->setPassword(
    //             $passwordEncoder->encodePassword(
    //                 $user,
    //                 $form->get('plainPassword')->getData()
    //             )
    //         );

    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->persist($user);
    //         $entityManager->flush();
    //         // do anything else you need here, like send an email

    //         // send le mail
    //         $emailService->send([
    //             'to' => $user->getEmail(),
    //             'subject' => 'Validez votre inscription',
    //             'template' => 'email\security\verify_email.html.twig',
    //             'context' => [
    //                 'user'=> $user
    //             ] ,

    //         ]);

    //         // coupe laconnexion direct et lui rederiger ver le login
    //         $this->addFlash('success', "Verifiez votre mail via le lien envoyé");
    //         return $this->redirectToRoute('app_login');

    //         // return $guardHandler->authenticateUserAndHandleSuccess(
    //         //     $user,
    //         //     $request,
    //         //     $authenticator,
    //         //     'main' // firewall name in security.yaml
    //         // );
    //     }

       
    //     return $this->render('registration/register.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    // }



    // /**
    //  * @Route("/verification-email/{token}", name="verify_email")
    //  */
    // // recupere le token qui est dans l'url
    // public function verifyEmail( string $token, 
    //     Encryptor $encryptor,
    //     UserRepository $userRepository,
    //     Request $request,
    //     GuardAuthenticatorHandler $guardHandler,
    //     LoginFormAuthenticator $authenticator
    // )
    // {
    //     $id =$encryptor->decrypt($token);
    //     $user= $userRepository->find($id);

    //     if (!$user) {
    //         throw $this->createNotFoundException();
    //         // throw new NotFoundHttpException("Votre compte n'a pas été trouvé");
    //     }

    //     $user->setEmailVerified(true);

        
    //     $entityManager = $this->getDoctrine()->getManager();
    //     $entityManager->flush();


    //     return $guardHandler->authenticateUserAndHandleSuccess(
    //             $user,
    //             $request,
    //             $authenticator,
    //             'main' // firewall name in security.yaml
    //         );


    // }
}
