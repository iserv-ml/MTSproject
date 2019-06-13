<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * TypeVehicule
 *
 * @ORM\Table(name="typeVehicule")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\TypeVehiculeRepository")
 * @UniqueEntity("libelle")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class TypeVehicule
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
     * 
     */
    private $libelle;
    
    /**
     * @var float $montantRevisite
     *
     * @ORM\Column(name="montantrevisite", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $montantRevisite;
    
    /**
     * @var float $montantVisite
     *
     * @ORM\Column(name="montantvisite", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $montantVisite;
    
    /**
     * @var integer $delai
     *
     * @ORM\Column(name="delai", type="integer", nullable=false)
     * @Assert\NotBlank
     */
    private $delai;
    
    /**
    * @ORM\ManyToOne(targetEntity="Genre", inversedBy="typeVehicules", cascade={"persist","refresh"})
    * @ORM\JoinColumn(name="genre_id", referencedColumnName="id")
    * @Assert\NotBlank
    */
    protected $genre;
    
    /**
    * @ORM\ManyToOne(targetEntity="Carrosserie", inversedBy="typeVehicules", cascade={"persist","refresh"})
    * @ORM\JoinColumn(name="carrosserie_id", referencedColumnName="id")
    * @Assert\NotBlank
    */
    protected $carrosserie;
    
    /**
    * @ORM\ManyToOne(targetEntity="Usage", inversedBy="typeVehicules", cascade={"persist","refresh"})
    * @ORM\JoinColumn(name="usage_id", referencedColumnName="id")
    * @Assert\NotBlank
    */
    protected $usage;
    
    //Debut relation TypeVehicule a plusieurs Vehicule
    /**
    * @ORM\OneToMany(targetEntity="Vehicule", mappedBy="typeVehicule", cascade={"persist"})
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
    //Fin relation typeVehicule a plusieurs Vehicule    

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
    
    function getGenre() {
        return $this->genre;
    }

    function getCarrosserie() {
        return $this->carrosserie;
    }

    function getUsage() {
        return $this->usage;
    }

    function setGenre($genre) {
        $this->genre = $genre;
    }

    function setCarrosserie($carrosserie) {
        $this->carrosserie = $carrosserie;
    }

    function setUsage($usage) {
        $this->usage = $usage;
    }
    
    function getLibelle() {
        return $this->libelle;
    }

    function setLibelle($libelle) {
        $this->libelle = $libelle;
    }
    function getMontantRevisite() {
        return $this->montantRevisite;
    }

    function getDelai() {
        return $this->delai;
    }

    function setMontantRevisite($montantRevisite) {
        $this->montantRevisite = $montantRevisite;
    }

    function setDelai($delai) {
        $this->delai = $delai;
    }
    function getMontantVisite() {
        return $this->montantVisite;
    }

    function setMontantVisite($montantVisite) {
        $this->montantVisite = $montantVisite;
    }
    
    function editerLibelle(){
        $this->libelle = $this->genre->getCode()."_".$this->carrosserie->getLibelle()."_".$this->usage->getLibelle();
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
        return $this->libelle;
    }
    
    public function __construct()
    {
        //$this->users = new ArrayCollection();
    }

}
