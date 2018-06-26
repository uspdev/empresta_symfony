<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\DataFixtures\TipoMaterialFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

use App\Entity\Material;

class MaterialFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for($i=1; $i<=250; $i++) {
            $material = new Material();
            $material->setAtivo(True);
            $material->setCodigo($i);
            $material->setTipo($this->getReference(TipoMaterialFixtures::TIPOMATERIAL_REFERENCE));
            $manager->persist($material);
            $manager->flush();
        }
    }

    public function getDependencies()
    {
        return array(
            TipoMaterialFixtures::class,
        );
    }
}
