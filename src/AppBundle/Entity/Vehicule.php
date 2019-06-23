<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Vehicule
 *
 * @ORM\Table(name="vehicule")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\VehiculeRepository")
 * @UniqueEntity("chassis")
 * @UniqueEntity("carteGrise")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Vehicule
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
     * @var string $chassis
     *
     * @ORM\Column(name="chassis", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $chassis;
    
    /**
     * @var string $carteGrise
     *
     * @ORM\Column(name="carteGrise", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $carteGrise;
    
    /**
     * @var string $immatriculation
     *
     * @ORM\Column(name="immatriculation", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $immatriculation;
    
    /**
     * @var datetime $dateCarteGrise
     *
     * @ORM\Column(name="dateCarteGrise", type="string", nullable=false)
     * @Assert\NotBlank
     * @Assert\Date
     */
    private $dateCarteGrise;
    
    /**
     * @var datetime $dateMiseCirculation
     *
     * @ORM\Column(name="dateMiseCirculation", type="string", nullable=false)
     * @Assert\NotBlank
     * @Assert\Date
     */
    private $dateMiseCirculation;
    
    /**
     * @var integer $ptac
     *
     * @ORM\Column(name="ptac", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $ptac;
    
    /**
     * @var integer $place
     *
     * @ORM\Column(name="place", type="integer", nullable=false)
     * @Assert\NotBlank
     */
    private $place;
    
    /**
     * @var integer $puissance
     *
     * @ORM\Column(name="puissance", type="integer", nullable=false)
     * @Assert\NotBlank
     */
    private $puissance;
    
    /**
     * @var integer $kilometrage
     *
     * @ORM\Column(name="kilometrage", type="integer", nullable=false)
     * @Assert\NotBlank
     */
    private $kilometrage;
    
    /**
     * @var string $couleur
     *
     * @ORM\Column(name="couleur", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $couleur;
    
    /**
    * @ORM\ManyToOne(targetEntity="Modele", inversedBy="vehicules", cascade={"persist","refresh"})
    * @ORM\JoinColumn(name="modele_id", referencedColumnName="id")
    * 
    */
    protected $modele;
    
    //Debut relation Vehicule a plusieurs visites
    /**
    * @ORM\OneToMany(targetEntity="Visite", mappedBy="vehicule", cascade={"persist"})
    */
    protected $visites;
    
    /**
    * Add visite
    *
    * @param AppBundle\Entity\Visite $visite
    */
    public function addVisite(\AppBundle\Entity\Visite $visite)
    {
        $this->visites[] = $visite;
    }

    /**
     * Get visites
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVisites()
    {
        return $this->visites;
    }

    /**
     * Set visites
     *
     * @param \Doctrine\Common\Collections\Collection $visites
     */
    public function setVisites(\Doctrine\Common\Collections\Collection $visites)
    {
        $this->visites = $visites;
    }
    //Fin relation vehicule a plusieurs visites
    
    protected $modeleAjax;
    protected $modeleId;
    
    protected $carrosserieAjax;
    protected $carrosserieId;
    
    protected $genreAjax;
    protected $genreId;

    protected $usageAjax;
    protected $usageId;  
    
    protected $proprietaireAjax;
    protected $proprietaireId;
    
    /**
    * @ORM\ManyToOne(targetEntity="Proprietaire", inversedBy="vehicules", cascade={"persist","refresh"})
    * @ORM\JoinColumn(name="proprietaire_id", referencedColumnName="id")
    * @Assert\NotBlank
    */
    protected $proprietaire;
    
    /**
    * @ORM\ManyToOne(targetEntity="TypeVehicule", inversedBy="vehicules", cascade={"persist","refresh"})
    * @ORM\JoinColumn(name="type_vehicule_id", referencedColumnName="id")
    * @Assert\NotBlank
    */
    protected $typeVehicule;
    
    /**
    * @ORM\ManyToOne(targetEntity="TypeImmatriculation", inversedBy="vehicules", cascade={"persist","refresh"})
    * @ORM\JoinColumn(name="type_immatriculation_id", referencedColumnName="id")
    * @Assert\NotBlank
    */
   protected $typeImmatriculation;

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
    
    function getChassis() {
        return $this->chassis;
    }

    function getCarteGrise() {
        return $this->carteGrise;
    }

    function getDateCarteGrise() {
        return $this->dateCarteGrise;
    }

    function getDateMiseCirculation() {
        return $this->dateMiseCirculation;
    }

    function getPtac() {
        return $this->ptac;
    }

    function getPlace() {
        return $this->place;
    }

    function getPuissance() {
        return $this->puissance;
    }

    function getKilometrage() {
        return $this->kilometrage;
    }

    function getCouleur() {
        return $this->couleur;
    }

    function getModele() {
        return $this->modele;
    }

    function setChassis($chassis) {
        $this->chassis = $chassis;
    }

    function setCarteGrise($carteGrise) {
        $this->carteGrise = $carteGrise;
    }

    function setDateCarteGrise($dateCarteGrise) {
        $this->dateCarteGrise = $dateCarteGrise;
    }

    function setDateMiseCirculation($dateMiseCirculation) {
        $this->dateMiseCirculation = $dateMiseCirculation;
    }

    function setPtac($ptac) {
        $this->ptac = $ptac;
    }

    function setPlace($place) {
        $this->place = $place;
    }

    function setPuissance($puissance) {
        $this->puissance = $puissance;
    }

    function setKilometrage($kilometrage) {
        $this->kilometrage = $kilometrage;
    }

    function setCouleur($couleur) {
        $this->couleur = $couleur;
    }

    function setModele($modele) {
        $this->modele = $modele;
    }
    
    function getProprietaire() {
        return $this->proprietaire;
    }

    function setProprietaire($proprietaire) {
        $this->proprietaire = $proprietaire;
    }
  
    function getImmatriculation() {
        return $this->immatriculation;
    }

    function setImmatriculation($immatriculation) {
        $this->immatriculation = $immatriculation;
    }
    function getTypeVehicule() {
        return $this->typeVehicule;
    }

    function getTypeImmatriculation() {
        return $this->typeImmatriculation;
    }

    function setTypeVehicule($typeVehicule) {
        $this->typeVehicule = $typeVehicule;
    }

    function setTypeImmatriculation($typeImmatriculation) {
        $this->typeImmatriculation = $typeImmatriculation;
    }
    
    function getModeleAjax() {
        return $this->modeleAjax;
    }

    function getModeleId() {
        return $this->modeleId;
    }

    function setModeleAjax($modeleAjax) {
        $this->modeleAjax = $modeleAjax;
    }

    function setModeleId($modeleId) {
        $this->modeleId = $modeleId;
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
        return $this->chassis;
    }

    public function estSupprimable(){
        return true;
    }
    
    public function __toString(){
        return $this->chassis;
    }
    
    public function __construct()
    {
        //$this->users = new ArrayCollection();
    }

}
