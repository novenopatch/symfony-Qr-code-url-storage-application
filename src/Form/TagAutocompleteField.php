<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Tag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\BaseEntityAutocompleteType;

#[AsEntityAutocompleteField]
class TagAutocompleteField extends AbstractType
{
public function configureOptions(OptionsResolver $resolver): void
{
    $resolver->setDefaults([
        'class' => Tag::class,
        'searchable_fields' => ['name'],
        'label' => 'Choice your Tag?',
        'choice_label' => 'name',
        'multiple' => true,
        'constraints' => [
            new Count(min: 1, minMessage: 'We need to eat *something*'),
        ],
        // 'security' => 'ROLE_SOMETHING',
    ]);
}

    public function getParent(): string
    {
        return BaseEntityAutocompleteType::class;
    }
}
