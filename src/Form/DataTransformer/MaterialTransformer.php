<?php

namespace App\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

use App\Entity\Emprestimo;
use App\Entity\Material;

class MaterialTransformer implements DataTransformerInterface
{
    private $entityManager;

    public function __construct(ObjectManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function transform($material)
    {
        if (null === $material) {
            return '';
        }

        return $material->getCodigo();
    }

public function reverseTransform($codigo)
{
    if (!$codigo) {
        return;
    }

    $material = $this->entityManager->getRepository('App:Material')->findOneBy(array('codigo' => $codigo));

    if (null === $material) {
        throw new TransformationFailedException(sprintf('There is no "%s" exists',
            $codigo
        ));
    }

    return $material;
}
}
