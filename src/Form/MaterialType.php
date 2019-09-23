<?php

namespace App\Form;

use App\Entity\Material;
use App\Entity\TipoMaterial;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PUGX\AutocompleterBundle\Form\Type\AutocompleteType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class MaterialType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('ativo', CheckboxType::class, array(
            'label'    => 'Ativo?',
            'required' => false,
            'help'=>'É possível desativar temporariamente o material para empréstimo. Ex. um armário quebrado'
        ));

        $builder
            ->add('codigo')
            ->add('tipo')
            //->add('tipo', AutocompleteType::class, ['class' => TipoMaterial::class])
            ->add('descricao')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Material::class,
        ]);
    }
}
