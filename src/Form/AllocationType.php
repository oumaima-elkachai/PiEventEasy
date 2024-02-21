<?php

namespace App\Form;

use App\Entity\Allocation;
use App\Entity\CategoryA;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class AllocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
        $builder
            ->add('nom')
            ->add('prix')
            ->add('date')
            ->add('quantity')
            ->add('category',EntityType::class,['class' => CategoryA::class,
            'choice_label' =>'nom',
            'label'=>'nom',

            ])
            ->add('image', FileType::class, [
                'label' => 'Image (JPEG, PNG, GIF)',
                'mapped' => false, 
                'required' => false, 
                'attr' => ['accept' => 'images/*'], 
            ])

            ->add('save',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Allocation::class,
        ]);
    }
}
