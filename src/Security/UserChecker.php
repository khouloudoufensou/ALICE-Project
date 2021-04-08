<?php
namespace App\Security;

use App\Entity\User as AppUser;
use App\Service\EmailService;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    // private $emailService;

    // public function __construct(EmailService $emailService)
    // {
    //     $this->emailService = $emailService;
    // }
    
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return;
        }

        if (!$user->getEmailVerified()) {
            throw new CustomUserMessageAccountStatusException('Merci de verifier votre email via le lien envoyé');

            // send le mail
            // $this->emailService->send([
            //     'to' => $user->getEmail(),
            //     'subject' => 'Validez votre inscription',
            //     'template' => 'email\security\verify_email.html.twig',
            //     'context' => [
            //         'user'=> $user
            //     ] ,

            // ]);

        }

        // if ($user->isDeleted()) {
        //     // the message passed to this exception is meant to be displayed to the user
        //     throw new CustomUserMessageAccountStatusException('Your user account no longer exists.');
        // }
    }

    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return;
        }

        // user account is expired, the user may be notified
        // if ($user->isExpired()) {
        //     throw new AccountExpiredException('...');
        // }
    }
}
?>