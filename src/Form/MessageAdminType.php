<?php

namespace App\Form;

use App\Entity\MessageAdmin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessageAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class,['label'=>'Nom:'])
            ->add('telephone')
            ->add('mail',EmailType::class,['label'=>'Votre mail:'])
            ->add('message',TextType::class,['label'=>'message'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MessageAdmin::class,
        ]);
    }
}
