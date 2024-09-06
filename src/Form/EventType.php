<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Event;
use App\Entity\Place;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la sortie',
            ])
            ->add('eventStart', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date et heure de la sortie',
            ])
            ->add('participationDeadline', DateType::class, [
                'widget' => 'single_text',
                'label' => "Date limite d'inscription",
            ])
            ->add('participantLimit', IntegerType::class, [
                'attr' => ['min' => '0'],
                'label' => 'Nombre de places',
            ])
            ->add('duration', IntegerType::class, [
                'attr' => ['min' => '0'],
                'label' => 'Durée de la sortie',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description et infos'
            ])
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'name',
                'label' => 'Campus',
            ])
            ->add('city', EntityType::class, [
                'class' => City::class,
                'choice_label' => 'name',
                'mapped' => false,
                'label' => 'Ville',
            ])
            ->add('place', EntityType::class, [
                'class' => Place::class,
                'choice_label' => 'name',
                'label' => 'Lieu'
            ])
            ->add('address', TextType::class, [
                'mapped' => false,
                'label' => 'Rue',
                'disabled' => true,
            ])
            ->add('zipcode', TextType::class, [
                'mapped' => false,
                'label' => 'Code postal',
                'disabled' => true,
            ])
            ->add('latitude', TextType::class, [
                'mapped' => false,
                'label' => 'Latitude',
                'disabled' => true
            ])
            ->add('longitude', TextType::class, [
                'mapped' => false,
                'label' => 'Longitude',
                'disabled' => true,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer'
            ])
            ->add('publish', SubmitType::class, [
                'label' => 'Publier'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
