<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MaterialRepository")
 */
class Material
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $ativo;

    /**
     * @ORM\Column(type="integer")
     */
    private $codigo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TipoMaterial", inversedBy="materiais")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tipo;

    public function __toString()
    {
        return $this->getId();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAtivo(): ?bool
    {
        return $this->ativo;
    }

    public function setAtivo(bool $ativo): self
    {
        $this->ativo = $ativo;

        return $this;
    }

    public function getCodigo(): ?int
    {
        return $this->codigo;
    }

    public function setCodigo(int $codigo): self
    {
        $this->codigo = $codigo;

        return $this;
    }

    public function getTipo(): ?TipoMaterial
    {
        return $this->tipo;
    }

    public function setTipo(?TipoMaterial $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }
}
