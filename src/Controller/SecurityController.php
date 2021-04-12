<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\LoginFormAuthenticator;
use App\Service\EmailService;
use Nzo\UrlEncryptorBundle\Encryptor\Encryptor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(
        AuthenticationUtils $authenticationUtils,
        Request $request, 
        UserPasswordEncoderInterface $passwordEncoder,
        EmailService $emailService
        
        ): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('redirect_user');
        }

        //  register

        $user = new User();
        $formRegistration = $this->createForm(RegistrationFormType::class, $user);
        $formRegistration->handleRequest($request);

        
        if ($formRegistration->isSubmitted() && $formRegistration->isValid()) {
            $user->setRoles(["ROLE_MEMBER"]);
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $formRegistration->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            // send le mail
            $emailService->send([
                'to' => $user->getEmail(),
                'subject' => 'Validez votre inscription',
                'template' => 'email\security\verify_email.html.twig',
                'context' => [
                    'user'=> $user
                ] ,

            ]);

            // coupe laconnexion direct et lui rederiger ver le login
            $this->addFlash('success', "Verifiez votre mail via le lien envoyé");
            return $this->redirectToRoute('app_login');

        }

         // login
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        // if ($error) {
        //     dd($error);
        // }



        return $this->render('security/connexion.html.twig', [
            'last_username' => $lastUsername, 
            'error' => $error,
            'form' => $formRegistration->createView()
            ]);
    }

    /**
     * @Route("/verification-email/{token}", name="verify_email")
     */
    // recupere le token qui est dans l'url
    public function verifyEmail( string $token, 
        Encryptor $encryptor,
        UserRepository $userRepository,
        Request $request,
        GuardAuthenticatorHandler $guardHandler,
        LoginFormAuthenticator $authenticator
    )
    {
        $id =$encryptor->decrypt($token);
        $user= $userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException();
            // throw new NotFoundHttpException("Votre compte n'a pas été trouvé");
        }

        $user->setEmailVerified(true);

        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();


        return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );


    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
