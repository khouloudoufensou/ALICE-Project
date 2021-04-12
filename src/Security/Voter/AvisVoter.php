<?php

namespace App\Security\Voter;

use App\Entity\Avis;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class AvisVoter extends Voter
{
    const DELETE ='delete';
    const EDIT ='edit';

    private $security;
    public function __construct( Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $avis)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::DELETE, self::EDIT]) && $avis instanceof Avis;
    }

    protected function voteOnAttribute($attribute, $avis, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                // logic to determine if the user can EDIT
                // return true or false
                return $this->canEdit($user, $avis);
                break;
            case self::DELETE:
                // logic to determine if the user can VIEW
                // return true or false
                return $this->canDelete($user, $avis);
                break;
        }

        return false;
    }
    public function canEdit( User $user, Avis $avis): bool
    {
        return $avis->getUser() === $user
            || $this->security->isGranted('ROLE_ADMIN');
    }

    public function canDelete(User $user, Avis $avis): bool
    {
        return $avis->getId() && 
            $this->canEdit( $user, $avis) ;
    }
}
