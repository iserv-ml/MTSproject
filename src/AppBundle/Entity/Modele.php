<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Modele
 *
 * @ORM\Table(name="modele")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ModeleRepository")
 * @UniqueEntity("code")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Modele
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
     * @var string $libelle
     *
     * @ORM\Column(name="libelle", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $libelle;
    
    /**
     * @var string $code
     *
     * @ORM\Column(name="code", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $code;
    
    /**
     * @var boolean $ancienneBase
     *
     * @ORM\Column(name="anciennebase", type="boolean", nullable=false)
     * 
     */
    private $ancienneBase;
    
    /**
    * @ORM\ManyToOne(targetEntity="Marque", inversedBy="modeles", cascade={"persist","refresh"})
    * @ORM\JoinColumn(name="marque_id", referencedColumnName="id")
    * @Assert\NotBlank
    */
    protected $marque;
    
    //Debut relation Modele a plusieurs vehicule
    /**
    * @ORM\OneToMany(targetEntity="Vehicule", mappedBy="modele", cascade={"persist"})
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
    //Fin relation modele a plusieurs vehicules

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

    function getLibelle() {
        return $this->libelle;
    }

    function setLibelle($libelle) {
        $this->libelle = $libelle;
    }
    
    function getCode() {
        return \trim($this->code);
    }

    function getMarque() {
        return $this->marque;
    }

    function setCode($code) {
        $this->code = $code;
    }

    function setMarque($marque) {
        $this->marque = $marque;
    }
    
    function getAncienneBase() {
        return $this->ancienneBase;
    }

    function setAncienneBase($ancienneBase) {
        $this->ancienneBase = $ancienneBase;
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
        return $this->vehicules == null || count($this->vehicules)==0;
    }
    
    public function __toString(){
        return $this->libelle;
    }
    
    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->ancienneBase = false;
    }

}
