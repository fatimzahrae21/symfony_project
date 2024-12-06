<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function __construct(private FormListenerFactory $listenerfactory)
    {
        
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name' ,TextType::class , [
                'empty_data' => ''])
            ->add('slug'  ,TextType::class , [
                'required' => false,
                'empty_data' => ''
                ])
           ->add('save' , SubmitType::class, [
            'label' => 'Enregistrer'
           ])
           ->addEventListener(FormEvents::PRE_SUBMIT, $this->listenerfactory->autoSlug('name'))
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->listenerfactory->timestamps())
;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}