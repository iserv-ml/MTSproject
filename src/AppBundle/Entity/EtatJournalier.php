<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Quittance
 *
 * @ORM\Table(name="etat_journalier")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\EtatJournalierRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class EtatJournalier
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
     * @var string $date
     *
     * @ORM\Column(name="date_visite", type="string", nullable=false)
     * 
     */
    private $date;
    
    /**
     * @var float $montantVisite
     *
     * @ORM\Column(name="montant_visite", type="float", nullable=false)
     * 
     */
    private $montantVisite;
    
    /**
     * @var float $montantRevisite
     *
     * @ORM\Column(name="montant_revisite", type="float", nullable=false)
     * 
     */
    private $montantRevisite;
    
    /**
     * @var float $anaser
     *
     * @ORM\Column(name="anaser", type="float", nullable=false)
     * 
     */
    private $anaser;
    
    /**
     * @var integer $nbvisite
     *
     * @ORM\Column(name="nbvisite", type="integer", nullable=false)
     * 
     */
    private $nbvisite;
    
    /**
     * @var integer $nbrevisite
     *
     * @ORM\Column(name="nbrevisite", type="integer", nullable=false)
     */
    private $nbrevisite;
    
    /**
     * @var string $caisse
     *
     * @ORM\Column(name="caisse", type="string", nullable=false)
     * 
     */
    private $caisse;
    
    /**
     * @var string $encaissePar
     *
     * @ORM\Column(name="encaisse_par", type="string", nullable=true)
     * 
     */
    private $encaissePar;
    
    /**
     * @var string $remboursePar
     *
     * @ORM\Column(name="rembourse_par", type="string", nullable=true)
     * 
     */
    private $remboursePar;
    
    /**
     * @var string $action
     *
     * @ORM\Column(name="action", type="string", nullable=true)
     * 
     */
    private $action;
    
    /**
     * @var string $typeVehicule
     *
     * @ORM\Column(name="type_vehicule", type="string", nullable=false)
     * 
     */
    private $typeVehicule;
    
    /**
     * @var string $genre
     *
     * @ORM\Column(name="genre", type="string", nullable=false)
     * 
     */
    private $genre;
    
    /**
     * @var string $usage
     *
     * @ORM\Column(name="usagetype", type="string", nullable=false)
     * 
     */
    private $usage;
    
    /**
     * @var string $carrosserie
     *
     * @ORM\Column(name="carrosserie", type="string", nullable=false)
     * 
     */
    private $carrosserie;
    
    /**
     * @var string $immatriculation
     *
     * @ORM\Column(name="immatriculation", type="string", length=255, nullable=true)
     * 
     * 
     */
    private $immatriculation;
    
    /**
     * @var string $quittance
     *
     * @ORM\Column(name="quittance", type="string", length=255, nullable=true)
     * 
     */
    private $quittance;
    
    /**
     * @var string $centre
     *
     * @ORM\Column(name="centre", type="string", length=255, nullable=true)
     * 
     */
    private $centre;
    
    /**
     * @var boolean $synchro
     *
     * @ORM\Column(name="synchro", type="boolean", nullable=true)
     * 
     */
    private $synchro;
    
    /**
     * @var datetime $dateSynchro
     *
     * @ORM\Column(name="date_synchro", type="datetime", length=255, nullable=true)
     * 
     */
    private $dateSynchro;
   

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
        return true;
    }
    
    public function __toString(){
        return $this->date;
    }
    
    public function __construct($date, $montantVisite, $montantRevisite, $nbVisite, $nbRevisite, $typeVehicule, $usage, $genre, $carrosserie, $caisse, $immatriculation = "", $quittance = "", $anaser = 0, $centre="NC", $encaissePar="", $action="", $remboursePar="")
    {
        $this->date = $date;
        $this->montantRevisite = $montantRevisite;
        $this->montantVisite = $montantVisite;
        $this->nbrevisite = $nbRevisite;
        $this->nbvisite = $nbVisite;
        $this->typeVehicule = $typeVehicule;
        $this->usage = $usage;
        $this->genre = $genre;
        $this->carrosserie = $carrosserie;
        $this->caisse = $caisse;
        $this->immatriculation = $immatriculation;
        $this->quittance = $quittance;
        $this->anaser = $anaser;
        $this->centre = $centre;
        $this->synchro = null;
        $this->dateSynchro = null;
        $this->encaissePar = $encaissePar;
        $this->action = $action;
        $this->remboursePar = $remboursePar;
    }
     
    function getDate() {
        return $this->date;
    }

    function getMontantVisite() {
        return $this->montantVisite;
    }

    function getMontantRevisite() {
        return $this->montantRevisite;
    }

    function getNbvisite() {
        return $this->nbvisite;
    }

    function getNbrevisite() {
        return $this->nbrevisite;
    }

    function getCaisse() {
        return $this->caisse;
    }

    function getTypeVehicule() {
        return $this->typeVehicule;
    }

    function getGenre() {
        return $this->genre;
    }

    function getUsage() {
        return $this->usage;
    }

    function getCarrosserie() {
        return $this->carrosserie;
    }

    function setDate($date) {
        $this->date = $date;
    }

    function setMontantVisite($montantVisite) {
        $this->montantVisite = $montantVisite;
    }

    function setMontantRevisite($montantRevisite) {
        $this->montantRevisite = $montantRevisite;
    }

    function setNbvisite($nbvisite) {
        $this->nbvisite = $nbvisite;
    }

    function setNbrevisite($nbrevisite) {
        $this->nbrevisite = $nbrevisite;
    }

    function setCaisse($caisse) {
        $this->caisse = $caisse;
    }

    function setTypeVehicule($typeVehicule) {
        $this->typeVehicule = $typeVehicule;
    }

    function setGenre($genre) {
        $this->genre = $genre;
    }

    function setUsage($usage) {
        $this->usage = $usage;
    }

    function setCarrosserie($carrosserie) {
        $this->carrosserie = $carrosserie;
    }
    
    function getImmatriculation() {
        return $this->immatriculation;
    }

    function setImmatriculation($immatriculation) {
        $this->immatriculation = $immatriculation;
    }
    
    public function getAnaser() {
        return $this->anaser;
    }

    public function getCentre() {
        return $this->centre != NULL ? $this->centre : "NC";
    }

    public function setAnaser($anaser) {
        $this->anaser = $anaser;
    }

    public function setCentre($centre) {
        $this->centre = $centre;
    }

    function getSynchro() {
        return $this->synchro;
    }

    function setSynchro($synchro) {
        $this->synchro = $synchro;
    }
    
    function getDateSynchro() {
        return $this->dateSynchro;
    }

    function setDateSynchro($dateSynchro) {
        $this->dateSynchro = $dateSynchro;
    }
    
    public function getQuittance() {
        return $this->quittance;
    }

    public function setQuittance($quittance) {
        $this->quittance = $quittance;
    }
    public function getCaissier() {
        return $this->caissier;
    }

    public function setCaissier($caissier) {
        $this->caissier = $caissier;
    }
    
    public function getEncaissePar() {
        return $this->encaissePar;
    }

    public function getRemboursePar() {
        return $this->remboursePar;
    }

    public function getAction() {
        return $this->action;
    }

    public function setEncaissePar($encaissePar) {
        $this->encaissePar = $encaissePar;
    }

    public function setRemboursePar($remboursePar) {
        $this->remboursePar = $remboursePar;
    }

    public function setAction($action) {
        $this->action = $action;
    }



}
