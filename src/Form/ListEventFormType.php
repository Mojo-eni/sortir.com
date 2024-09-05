<?php

namespace App\Form;

use App\Entity\Event;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListEventFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('campus', EntityType::class, [
                'mapped' => false,
                'class' => 'App\Entity\Campus',
                'choice_label' => 'name',
                'label' => "Campus"
            ])
            ->add('name', TextType::class, [
                'mapped' => false,
                'label' => 'Keyword',
                'required' => false,
            ])
            ->add('dateFrom', DateType::class, [
                'mapped' => false,
                'widget' => 'single_text',
                'label' => 'From',
                'required' => false,
            ])
            ->add('dateTo', DateType::class, [
                'mapped' => false,
                'widget' => 'single_text',
                'label' => 'To',
                'required' => false,
            ])
            ->add('isOrganizer', CheckboxType::class, [
                'mapped' => false,
                'label' => 'Organizer',
                'required' => false,
            ])
            ->add('isAttending', CheckboxType::class, [
                'mapped' => false,
                'label' => 'Attendee',
                'required' => false,
            ])
            ->add('isNotAttending', CheckboxType::class, [
                'mapped' => false,
                'label' => 'Not going',
                'required' => false,
            ])
            ->add('hasPassed', CheckboxType::class, [
                'mapped' => false,
                'label' => 'Past',
                'required' => false,
            ])
            ->add('search', SubmitType::class, [
                'label' => 'Search',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
