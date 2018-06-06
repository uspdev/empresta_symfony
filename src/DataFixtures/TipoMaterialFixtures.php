<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\TipoMaterial;

class TipoMaterialFixtures extends Fixture
{
    public const TIPOMATERIAL_REFERENCE = 'Armário';

    public function load(ObjectManager $manager)
    {
        $tipo = new TipoMaterial();
        $tipo->setNome('Armário');
        $manager->persist($tipo);

        $manager->flush();

        $this->addReference(self::TIPOMATERIAL_REFERENCE, $tipo);
    }
}
