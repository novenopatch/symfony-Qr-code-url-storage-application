<?php

namespace App\Security\Voter;

use App\Utils\Constant;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class QrcodeVoter extends Voter
{
    public  const EDIT = 'QRCODE_EDIT';
    public const VIEW = 'QRCODE_VIEW';

    public function __construct(private readonly Security $security)
    {

    }
    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::VIEW])
            && $subject instanceof \App\Entity\Qrcode;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if($subject->getAuthor()==null && $attribute== self::VIEW) return true;
        if (!$user instanceof UserInterface|| $subject->getAuthor()==null) {
            return false;
        }
        switch ($attribute) {

            case self::EDIT:
               return $subject->getAuthor() === $user;
            case self::VIEW:
                if($this->security->isGranted(Constant::ROLE_ADMIN)) return true;
                return $subject->getAuthor() === $user;

        }

        return false;
    }
}
