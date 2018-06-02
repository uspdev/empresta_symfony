<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Emprestimo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VisitanteRepository")
 */
class Visitante
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
     * @ORM\Column(type="string", length=255)
     */
    private $telefone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Emprestimo", mappedBy="visitante")
     */
    private $emprestimos;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    private $createdBy;

    public function __toString()
    {
        return $this->getNome();
    }

    public function __construct()
    {
        $this->emprestimos = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }

    public function getTelefone(): ?string
    {
        return $this->telefone;
    }

    public function setTelefone(string $telefone): self
    {
        $this->telefone = $telefone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

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

    public function setEmprestimos($emprestimos)
    {
        $this->emprestimos = $emprestimos;
        return $this;
    }

    public function getEmprestimos()
    {
        return $this->emprestimos;
    }

    public function addEmprestimo(Emprestimo $emprestimo)
    {
        $this->emprestimos[] = $emprestimo;

        return $this;
    }

    public function removeEmprestimo(Emprestimo $emprestimo)
    {
        $this->emprestimos->removeElement($emprestimo);
    }
}
