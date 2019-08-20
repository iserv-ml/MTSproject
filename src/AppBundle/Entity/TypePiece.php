<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * TypePiece
 *
 * @ORM\Table(name="typepiece")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\TypePieceRepository")
 * @UniqueEntity("code")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class TypePiece
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /** 
     * @ORM\Version 
     * @ORM\Column(type="integer") 
     */
    private $version;
    
    /**
     * @var string $code
     *
     * @ORM\Column(name="code", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $code;
    
    /**
     * @var string $libelle
     *
     * @ORM\Column(name="libelle", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $libelle;
    
    
    //Debut relation typePiece a plusieurs Proprietaires
    /**
    * @ORM\OneToMany(targetEntity="Proprietaire", mappedBy="typePiece", cascade={"persist"})
    */
    protected $proprietaires;
    
    /**
    * Add proprietaire
    *
    * @param AppBundle\Entity\Proprietaire $proprietaire
    */
    public function addProprietaire(\AppBundle\Entity\Proprietaire $proprietaire)
    {
        $this->proprietaires[] = $proprietaire;
    }

    /**
     * Get proprietaires
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProprietaires()
    {
        return $this->proprietaires;
    }

    /**
     * Set proprietaires
     *
     * @param \Doctrine\Common\Collections\Collection $proprietaires
     */
    public function setProprietaires(\Doctrine\Common\Collections\Collection $proprietaires)
    {
        $this->proprietaires = $proprietaires;
    }
    //Fin relation typePiece a plusieurs proprietaires    
    

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    public function getVersion() {
        return $this->version;
    }

    public function setVersion($version) {
        $this->version = $version;
    }
    
    function getCode() {
        return $this->code;
    }

    function setCode($code) {
        $this->code = $code;
    }
    
    function getLibelle() {
        return $this->libelle;
    }

    function setLibelle($libelle) {
        $this->libelle = $libelle;
    }
    
    //BEHAVIOR
    /**
     * @var string $creePar
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\Column(type="string")
     */
    private $creePar;

    /**
     * @var string $modifierPar
     *
     * @Gedmo\Blameable(on="update")
     * @ORM\Column(type="string")
     */
    private $modifierPar;
    
    /**
     * @ORM\Column(name="deletedAt", type="datetime", nullable=true)
     */
    private $deletedAt;
    
    /**
     * @var \DateTime $dateCreation
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $dateCreation;

    /**
     * @var \DateTime $dateModification
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $dateModification;
    
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;
    }
    public function getCreePar() {
        return $this->creePar;
    }

    public function getModifierPar() {
        return $this->modifierPar;
    }

    public function getDateCreation() {
        return $this->dateCreation;
    }

    public function getDateModification() {
        return $this->dateModification;
    }

    public function setCreePar($creePar) {
        $this->creePar = $creePar;
    }

    public function setModifierPar($modifierPar) {
        $this->modifierPar = $modifierPar;
    }

    public function setDateCreation(\DateTime $dateCreation) {
        $this->dateCreation = $dateCreation;
    }

    public function setDateModification(\DateTime $dateModification) {
        $this->dateModification = $dateModification;
    }

    public function estSupprimable(){
        return $this->proprietaires == null || count($this->proprietaires)==0;
    }
    
    public function __toString(){
        return $this->libelle;
    }
    
    public function __construct()
    {
        $this->proprietaires = new ArrayCollection();
    }

}
