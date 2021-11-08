<?php

namespace App\Security\Voter;

use App\Entity\Article;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class ArticleVoter extends Voter
{
    private Security $security;
    
    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    
    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, ['MANAGE', 'API'])
            && $subject instanceof Article;
    }
    
    /**
     * @param string $attribute
     * @param mixed|Article $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        
        if (!$user instanceof UserInterface) {
            return false;
        }
        
        switch ($attribute) {
            case 'MANAGE':
                if ($this->security->isGranted('ROLE_ADMIN_ARTICLE')) {
                    return true;
                }
                break;
                
            case 'API':
                if ($this->security->isGranted('ROLE_API')) {
                    return true;
                }
                break;
                
            default:
                if ($subject->getAuthor() === $user) {
                    return true;
                }
                break;
        }
        
        return false;
    }
}
