<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('password',PasswordType::class,[
                'help' => 'Deixe em branco para não alterar a senha',
                'required'   => false,
            ])
        ;

        $builder->add('roles', ChoiceType::class, array(
                       'multiple' => true,
                       'expanded' => true,
                       'choices' => array(
                            'Balcão' => 'ROLE_USER',
                            'Administrador do Sistema' => 'ROLE_ADMIN',
                        )
                    ));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
