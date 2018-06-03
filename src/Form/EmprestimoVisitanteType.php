<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\DataTransformer\MaterialTransformer;
use PUGX\AutocompleterBundle\Form\Type\AutocompleteType;
use App\Entity\Visitante;

class EmprestimoVisitanteType extends AbstractType
{
    private $transformer;

    public function __construct(MaterialTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('visitante', AutocompleteType::class, ['class' => Visitante::class]);

        $builder->add('material',TextType::class,[
            'required'  => true,
            'invalid_message' => 'Item não encontrado. Código Inexistente',
        ]);

       $builder->get('material')
            ->addModelTransformer($this->transformer);

    }

    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Emprestimo'
        ));
    }
}
