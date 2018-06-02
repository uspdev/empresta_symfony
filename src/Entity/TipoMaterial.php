<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TipoMaterialRepository")
 */
class TipoMaterial
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nome;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Material", mappedBy="tipo")
     */
    private $materiais;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    private $createdBy;

    public function __construct()
    {
        $this->materiais = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getNome();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(?string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }


    /**
     * @return Collection|Material[]
     */
    public function getMateriais(): Collection
    {
        return $this->materiais;
    }

    public function addMateriai(Material $materiai): self
    {
        if (!$this->materiais->contains($materiai)) {
            $this->materiais[] = $materiai;
            $materiai->setTipo($this);
        }

        return $this;
    }

    public function removeMateriai(Material $materiai): self
    {
        if ($this->materiais->contains($materiai)) {
            $this->materiais->removeElement($materiai);
            // set the owning side to null (unless already changed)
            if ($materiai->getTipo() === $this) {
                $materiai->setTipo(null);
            }
        }

        return $this;
    }
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }
}
