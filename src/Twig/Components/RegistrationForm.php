<?php
namespace App\Twig\Components;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class RegistrationForm extends AbstractController
{
    use ComponentWithFormTrait;
    use DefaultActionTrait;
    #[LiveProp]
    public bool $isSuccessful = false;

    #[LiveProp]
    public ?string $newUserEmail = null;

    public function __construct(private readonly EntityManagerInterface $entityManager,private UserPasswordHasherInterface $userPasswordHasher,private EmailVerifier $emailVerifier)
    {
    }

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(RegistrationFormType::class);
    }

    public function hasValidationErrors(): bool
    {

        return $this->getForm()->isSubmitted() && !$this->getForm()->isValid() ? 422 : 200;
    }

    #[LiveAction]
    public function saveRegistration(): void
    {
        $this->submitForm();

       $this->saveNewUser();
        $this->isSuccessful = true;
    }
    public function saveNewUser(): void{
        $plainPassword =$this->getForm()->get('plainPassword')->getData();
        $user = new User();
        $user->setEmail($this->getForm()->get('email')->getData());
        $user->setFullName($this->getForm()->get('fullName')->getData());
        $user->setPassword($this->userPasswordHasher->hashPassword($user, $plainPassword));
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
            (new TemplatedEmail())
                ->from(new Address('qrcode@your-domain.com', 'Qrcodeserver'))
                ->to((string) $user->getEmail())
                ->subject('Please Confirm your Email')
                ->htmlTemplate('pages/security/registration/confirmation_email.html.twig')
        );
        $this->newUserEmail = $this->getForm()
            ->get('email')
            ->getData();
    }
}