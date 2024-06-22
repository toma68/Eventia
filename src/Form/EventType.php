<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;


class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('date', null, [
                'widget' => 'single_text',
                'html5' => true,
                'constraints' => [
                    new GreaterThan([
                        'value' => 'now',
                        'message' => 'The event date must be in the future.',
                    ]),
                ],
                'attr' => [
                    'min' => (new \DateTime())->format('Y-m-d\TH:i'),
                ],

            ])
            ->add('maxpeople')
            ->add('public')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
