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
 * @ORM\Table(name="formatImmatriculation")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\FormatImmatriculationRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @UniqueEntity("presentation")
 */
class FormatImmatriculation
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
     * @var string $presentation
     *
     * @ORM\Column(name="presentation", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $presentation;
    
    /**
     * @var string $regex
     *
     * @ORM\Column(name="regex", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $regex;
    
    /**
     * @var boolean $actif
     *
     * @ORM\Column(name="actif", type="boolean", nullable=false)
     * 
     */
    private $actif;
    
    /**
    * @ORM\ManyToOne(targetEntity="TypeImmatriculation", inversedBy="formats", cascade={"persist","refresh"})
    * @ORM\JoinColumn(name="type_immatriculation_id", referencedColumnName="id")
    * @Assert\NotBlank
    */
   protected $typeImmatriculation;
    
    //Debut relation TypeImmatriculation a plusieurs vehicules
    /**
    * @ORM\OneToMany(targetEntity="Vehicule", mappedBy="typeImmatriculation", cascade={"persist"})
    */
    protected $vehicules;
    
    /**
    * Add vehicule
    *
    * @param AppBundle\Entity\Vehicule $vehicule
    */
    public function addVehicule(\AppBundle\Entity\Vehicule $vehicule)
    {
        $this->vehicules[] = $vehicule;
    }

    /**
     * Get vehicules
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVehicules()
    {
        return $this->vehicules;
    }

    /**
     * Set vehicules
     *
     * @param \Doctrine\Common\Collections\Collection $vehicules
     */
    public function setVehicules(\Doctrine\Common\Collections\Collection $vehicules)
    {
        $this->vehicules = $vehicules;
    }
    //Fin relation typeImmatriculation a plusieurs vehicules
    
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
    
    function getPresentation() {
        return $this->presentation;
    }

    function getRegex() {
        return $this->regex;
    }

    function getTypeImmatriculation() {
        return $this->typeImmatriculation;
    }

    function setPresentation($presentation) {
        $this->presentation = $presentation;
    }

    function setRegex($regex) {
        $this->regex = $regex;
    }

    function setTypeImmatriculation($typeImmatriculation) {
        $this->typeImmatriculation = $typeImmatriculation;
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
        return $this->vehicules == null || count($this->vehicules) == 0;
    }
    
    public function __toString(){
        return $this->presentation;
    }
    
    public function __construct()
    {
        $this->immatriculations = new ArrayCollection();
    }
    //fin behavior

    function getActif() {
        return $this->actif;
    }

    function setActif($actif) {
        $this->actif = $actif;
    }

}
