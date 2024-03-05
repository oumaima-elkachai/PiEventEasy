<?php

namespace App\Form;

use App\Entity\BookingL;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class BookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prix',NumberType::class, [
                'label' => 'Prix total',
                'scale' => 2, // Nombre de décimales à afficher
                'disabled' => true, // Rendre le champ non modifiable
            ])
            ->add('DateD', DateType::class, [
                'label' => 'Choose date',
                'required' => true ,
                'data' => new \DateTime(),
                'widget' => 'single_text',
                
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => 'today',
                        'message' => 'La date doit être supérieures ou égales à la date actuelle.',
                    ]),
                ],
                'attr' => [
                    'min' => (new \DateTime())->format('Y-m-d'),
                    'class' => 'form-control datetimepicker-input',
                ],
               
            ])
            ->add('DateF', DateType::class, [
                'label' => 'Choose date',
                'required' => true ,
                'data' => new \DateTime(),
                'widget' => 'single_text',
                
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => 'today',
                        'message' => 'La date doit être supérieures ou égales à la date actuelle.',
                    ]),
                ],
                'attr' => [
                    'min' => (new \DateTime())->format('Y-m-d'),
                    'class' => 'form-control datetimepicker-input',
                ],
               
            ])
            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'booking',
            ])
            
            ->add('save',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BookingL::class,
        ]);
    }
}
