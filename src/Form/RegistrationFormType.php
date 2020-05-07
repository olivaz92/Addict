<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

                ->add('pseudo', TextType::class,[
                    'label'=>'Pseudonyme:'
                ])
                ->add('mail', EmailType::class,[
                    'label'=>'Adresse mail:',
                    'mapped' => true,
                ])
                ->add('photo', FileType::class, array(  'data_class' => null,'label'=>'Photo: ','required'   => false,))
                ->add('plainPassword', PasswordType::class, [
                    // instead of being set onto the object directly,
                    // this is read and encoded in the controller
                    'label'=>'Mot de passe: ',
                    'mapped' => false,
                    'required'=>false,
                    'constraints' => [
                new NotBlank([
                    'message' => 'Please enter a password',
                ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Votre mot de passe doit contenir au minimum {{ limit }} caractères',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ],
                ])
                ->add('sexe', ChoiceType::class,[
                    'label'=>'Sexe: ',
                    'choices' => [
                        'Homme' => 'H',
                        'Femme' => 'F',
                    ]])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
