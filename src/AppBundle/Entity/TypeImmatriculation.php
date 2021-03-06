<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Marque
 *
 * @ORM\Table(name="typeimmatriculation")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\TypeImmatriculationRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @UniqueEntity("code")
 */
class TypeImmatriculation
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
     * @var string $description
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $description;
    
    /**
     * @var string $code
     *
     * @ORM\Column(name="code", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $code;

    //Debut relation TypeImmatriculation a plusieurs formats
    /**
    * @ORM\OneToMany(targetEntity="FormatImmatriculation", mappedBy="typeImmatriculation", cascade={"persist"})
    */
    protected $formats;
    
    /**
    * Add format
    *
    * @param AppBundle\Entity\FormatImmatriculation $format
    */
    public function addVehicule(\AppBundle\Entity\FormatImmatriculation $format)
    {
        $this->formats[] = $format;
    }

    /**
     * Get formats
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFormats()
    {
        return $this->formats;
    }

    /**
     * Set formats
     *
     * @param \Doctrine\Common\Collections\Collection $formats
     */
    public function setFormats(\Doctrine\Common\Collections\Collection $formats)
    {
        $this->formats = $formats;
    }
    //Fin relation typeImmatriculation a plusieurs formats
    
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
    
    function getDescription() {
        return $this->description;
    }

    function getCode() {
        return $this->code;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    function setCode($code) {
        $this->code = $code;
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
    
    public function getNomComplet(){
        return $this->libelle;
    }

    public function estSupprimable(){
        return true;
    }
    
    public function __toString(){
        return $this->code;
    }
    
    public function __construct()
    {
        $this->immatriculations = new ArrayCollection();
    }
    //fin behavior


}
