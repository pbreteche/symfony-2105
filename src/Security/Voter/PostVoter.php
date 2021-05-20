<?php

namespace App\Security\Voter;

use App\Entity\Post;
use App\Repository\AuthorRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class PostVoter extends Voter
{

    /**
     * @var \App\Repository\AuthorRepository
     */
    private $authorRepository;

    public function __construct(AuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }

    protected function supports(string $attribute, $subject): bool
    {
        return 'POST_EDIT' === $attribute
            && $subject instanceof Post;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        $author = $this->authorRepository->findOneBy(['user' => $user]);

        return $author === $subject->getWrittenBy();
    }
}
