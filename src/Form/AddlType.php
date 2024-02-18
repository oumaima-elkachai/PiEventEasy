<?php

namespace App\Form;

use App\Entity\CategoryL;
use App\Entity\Lieu;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class AddlType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prix')
            
            ->add('date', DateType::class, [
                'label' => 'Choisissez votre date',
                'required' => true ,
                'data' => new \DateTime(),
                'widget' => 'single_text',
                
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => 'today',
                        'message' => 'La date et l\'heure doivent être supérieures ou égales à la date actuelle.',
                    ]),
                ],
                'attr' => [
                    'min' => (new \DateTime())->format('Y-m-d'),
                    'class' => 'form-control datetimepicker-input',
                ],
               
            ])
            ->add('image', FileType::class, [
                'label' => 'service Picture',
                'mapped' => false,
                'required' => false, // Set to true if the photo is mandatory
                // Add any other options you need, such as validation constraints
            ])
            ->add('category',EntityType::class,['class' => CategoryL::class,
            'choice_label' =>'nom',
            'label'=>'nom',

            ])
            ->add('save',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lieu::class,
        ]);
    }
}
