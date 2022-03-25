<?php

namespace App\Post\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Security;
use App\Post\Entity\Post;

class PostVoter extends Voter
{
    private $security;
    
    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    
    protected function supports($attribute, $subject)
    {
        return $attribute === 'EDIT'
            && $subject instanceof Post;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        // on retrouve l'utilisateur (on peut aussi ré-utiliser $this->security)
        $user = $token->getUser();

        // si l'utilisateur n'est pas authentifié, c'est non!
        if (!$user instanceof UserInterface) {
            return false;
        }

        // l'utilisateur est l'auteur de l'article
        if ($user === $subject->getAuthor()) {
            return true;
        }

        // l'utilisateur est un administrateur
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        return false;
    }
}
