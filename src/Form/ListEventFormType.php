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
            ->add('keyword', TextType::class, [
                'mapped' => false,
                'label' => 'Mot-clé',
                'required' => false,
            ])
            ->add('dateFrom', DateType::class, [
                'mapped' => false,
                'widget' => 'single_text',
                'label' => 'Du',
                'required' => false,
            ])
            ->add('dateTo', DateType::class, [
                'mapped' => false,
                'widget' => 'single_text',
                'label' => 'Au',
                'required' => false,
            ])
            ->add('isOrganizer', CheckboxType::class, [
                'mapped' => false,
                'label' => 'Organisateur',
                'required' => false,
            ])
            ->add('isAttending', CheckboxType::class, [
                'mapped' => false,
                'label' => 'Participant',
                'required' => false,
            ])
            ->add('isNotAttending', CheckboxType::class, [
                'mapped' => false,
                'label' => 'Non participant',
                'required' => false,
            ])
            ->add('hasPassed', CheckboxType::class, [
                'mapped' => false,
                'label' => 'Passées',
                'required' => false,
            ])
            ->add('search', SubmitType::class, [
                'label' => 'Recherche',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
        ]);
    }
}
