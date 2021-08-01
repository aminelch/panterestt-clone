<?php

namespace App\Security\Voter;

use App\Entity\Pin;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class PinVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html

       
        return $attribute === 'PIN_CREATE' || (in_array($attribute, ['PIN_MANAGE']))
            && $subject instanceof (Pin::class) ;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
     
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
 
            case 'PIN_CREATE':
                return $user->isVerified() ; 

            case 'PIN_MANAGE':
                return $user->isVerified() && $user == $subject->getUser(); 
            
            // case 'PIN_DELETE':
            //     return $user->isVerified() && $user == $subject->getUser(); 
    
      
        }

        return false;
    }
}
