<?php

namespace App\Form;

use App\Entity\Recipe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\Constraints\Length;

class RecipeType extends AbstractType
{
    public function __construct(private FormListenerFactory $listenerfactory)
    {
        
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('slug' , TextType::class , [
                'required' => false ,
                // 'constraints' => new Length(min: 10)
            ])
            ->add('contenu')
            // ->add('createdAt', null, [
            //     'widget' => 'single_text',
            // ])
            // ->add('updatedAt', null, [
            //     'widget' => 'single_text',
            // ])
            ->add('duration')
            ->add('Save' ,SubmitType::class)
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->listenerfactory->autoSlug('title'))
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->listenerfactory->timestamps())

        ;
    }
   

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
