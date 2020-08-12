<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Tag;
use App\Entity\Thing;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tag', EntityType::class, [
                'mapped' => null,
                'class' => Tag::class,
                'choice_label' => function ($tag) {
                    return ucfirst($tag->getName());
                },
                'attr' => [
                    'class' => 'custom-select',
                ],
            ])
            ->add('thing', EntityType::class, [
                'class' => Thing::class,
                'choice_label' => function ($thing) {
                    return ucfirst($thing->getBrand()) . '-' . ucfirst($thing->getModel()) . '-' . $thing->getIdentificationNumber();
                },
                'attr' => [
                    'class' => 'custom-select',
                ],
            ])
            ->add('start', DateTimeType::class, [
                'label' => 'Start',
                'attr' => ['class' => 'form-control'],
                'widget' => 'single_text',
            ])
            ->add('end', DateTimeType::class, [
                'label' => 'Start',
                'attr' => ['class' => 'form-control'],
                'widget' => 'single_text',
            ])
            ->add('comment', TextareaType::class, [
                'attr' => [
                    'class' => 'comment form-control w-100 h-100',
                    'placeholder' => 'Let us know what you want',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
