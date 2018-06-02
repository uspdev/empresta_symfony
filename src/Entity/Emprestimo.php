<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Emprestimo
 *
 * @ORM\Table(name="emprestimo")
 * @ORM\Entity(repositoryClass="App\Repository\EmprestimoRepository")
 */
class Emprestimo
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

   /**
     * @ORM\ManyToOne(targetEntity="Material",inversedBy="emprestimos")
     * @ORM\JoinColumn(nullable=false)
     * @ORM\OrderBy({"id" = "ASC"})
     */
    private $material;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="data_emprestimo", type="datetime")
     */
    private $dataEmprestimo;

    /**
     * @var string
     *
     * @ORM\Column(name="data_devolucao", type="datetime",nullable=true)
     */
    private $dataDevolucao;

    /**
     * @var Author
     *
     * @ORM\ManyToOne(targetEntity="Visitante", inversedBy="emprestimos")
     * @ORM\JoinColumn(nullable=true)
     */
    private $visitante;

    /**
     * @var string
     *
     * @ORM\Column(name="codpes", type="string", length=255,nullable=true)
     */
    private $codpes;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    private $createdBy;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function setMaterial($material)
    {
        $this->material = $material;
        return $this;
    }

    public function getmaterial()
    {
        return $this->material;
    }

    /**
     * Set dataEmprestimo
     *
     * @param \DateTime $dataEmprestimo
     *
     * @return Emprestimo
     */
    public function setDataEmprestimo($dataEmprestimo)
    {
        $this->dataEmprestimo = $dataEmprestimo;

        return $this;
    }

    /**
     * Get dataEmprestimo
     *
     * @return \DateTime
     */
    public function getDataEmprestimo()
    {
        return $this->dataEmprestimo;
    }

    /**
     * Set dataDevolucao
     *
     * @param string $dataDevolucao
     *
     * @return Emprestimo
     */
    public function setDataDevolucao($dataDevolucao)
    {
        $this->dataDevolucao = $dataDevolucao;

        return $this;
    }

    /**
     * Get dataDevolucao
     *
     * @return string
     */
    public function getDataDevolucao()
    {
        return $this->dataDevolucao;
    }

    public function getCodpes()
    {
        return $this->codpes;
    }

    public function setCodpes($codpes)
    {
        $this->codpes = $codpes;
        return $this;
    }

    public function getVisitante()
    {
        return $this->visitante;
    }

    public function setVisitante($visitante)
    {
        $this->visitante = $visitante;
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
