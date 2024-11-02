<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Qrcode;
use App\Entity\Tag;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class QrcodeType extends AbstractType
{

    public function __construct(private readonly UrlGeneratorInterface $urlGenerator)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('data',TextType::class,[
                'label'=>'Text /url',
            ])
            ->add('description',TextareaType::class,[
                'label'=>'Description',
            ])
            ->add('category',EntityType::class,[
                'class' => Category::class,
                'label'=>'Category',
                'multiple' => false,
                'placeholder' => 'Choose an category',
                'required' => false,
            ])
           // ->add('tags',SearchableEntityType::class,['class'=>Tag::class, 'search'=>$this->urlGenerator->generate('api_tag'), 'required'=>false,])
               ->add('tags',TagAutocompleteField::class)
            ->add('submit',SubmitType::class,[
                'label'=>'Generate',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Qrcode::class,
        ]);
    }
}
