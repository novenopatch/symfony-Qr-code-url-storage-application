<?php

namespace App\Form;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;

class SearchableEntityType extends AbstractType
{

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function configureOptions(OptionsResolver $resolver)
    {

        $resolver->setRequired('class');
        $resolver->setDefaults([
            'compound' => false,
            'multiple' => true,
            'search'=>'/search',
            'value_property'=>'id',
            'label_field'=>'name'
        ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['expanded']= false;
        $view->vars['placeholder']= null;
        $view->vars['placeholder_in_choices']= false;
        $view->vars['multiple']= true;
        $view->vars['choices']= $this->getChoices($form->getData());
        $view->vars['preferred_choices'] = [];
        $view->vars['choice_translation_domain'] = false;
        $view->vars['full_name'] .= '[]';
        $view->vars['attr']['data-remote']= $options['search'];
        $view->vars['attr']['data-value']= $options['value_property'];
        $view->vars['attr']['data-label']= $options['label_field'];
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new CallbackTransformer(
            function ($data):array {

                return $data!=null?$data->map(fn($d) => (string)$d->getId())->toArray():[];
            },
            function (?array $ids) use ($options):Collection{
                if(empty($ids)||$ids==null){
                    return new ArrayCollection([]);
                }
                return new ArrayCollection(
                    $this->entityManager->getRepository($options['class'])->findBy(['id' => $ids])
                );
            }
        ));
    }

    public function getBlockPrefix()
    {
        return 'choice';
    }

    private function getChoices(Collection $datas)
    {
        return $datas
            ->map(fn ($data) => new ChoiceView($data,(string)$data->getId(),(string)$data))
            ->toArray();
    }
}