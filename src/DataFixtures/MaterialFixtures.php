<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\TipoMaterial;
use App\Entity\Material;

class TipoMaterialFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $tipo = new TipoMaterial();
        $tipo->setNome('Armário');
        $manager->persist($tipo);

        $manager->flush();

        $this->addReference(self::TIPOMATERIAL_REFERENCE, $tipo);
    }
}

class MaterialFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $material = new Material();
        $material->setNome('Armário');
        $material->setAtivo(True);
        $material->setCodigo('1');
        $material->setTipo($this->getReference(UserFixtures::TIPOMATERIAL_REFERENCE));
        $manager->flush();
    }
}
