<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

use Symfonycasts\DynamicForms\DynamicFormBuilder;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
        ->add('email',EmailType::class,[
            'attr'=>['autofocus'=>true],
            'constraints' => [
                new NotBlank(message: 'Please enter an email!'),
                new Email(),
            ],
            ])
            ->add('fullName',TextType::class,[
                'constraints' => [
                    new NotBlank(message: 'Please enter your full name!'),
                ],
            ])

            ->add('plainPassword', PasswordType::class, [
                'constraints' => [
                    new NotBlank(message: 'Please enter a password'),
                    new Length(min: 5, minMessage: 'Surely you can think of something longer than that!'),

                ],
                'always_empty' => false,
                'mapped' => false,
                'required' => true,
                'toggle' => true,
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
