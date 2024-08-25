<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Certificat
 *
 * @ORM\Table(name="certificat")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\CertificatRepository")
 * @UniqueEntity({"serie", "annee"})
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @Gedmo\Loggable
 */
class Certificat
{
    
    const digit = 7;

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
     * @var integer $serie
     * @Gedmo\Versioned
     * @ORM\Column(name="serie", type="integer", nullable=false)
     */
    private $serie;
    
    /**
     * @var integer $anne
     * @Gedmo\Versioned
     * @ORM\Column(name="annee", type="integer", nullable=true)
     */
    private $annee;
    
    /**
     * @var boolean $utilise
     *
     * @ORM\Column(name="utilise", type="boolean", nullable=true)
     * 
     */
    private $utilise;
    
    /**
     * @var String $immatriculation
     * @Gedmo\Versioned
     * @ORM\Column(name="immatriculation", type="string", length=255, nullable=true)
     */
    private $immatriculation;
    
    /**
     * @var boolean $annule
     *
     * @ORM\Column(name="annule", type="boolean", nullable=true)
     * 
     */
    private $annule;
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur", inversedBy="certificats", cascade={"persist","refresh"})
     * @ORM\JoinColumn(name="controleur_id", referencedColumnName="id")
     * @Gedmo\Versioned
     */
    private $controlleur;
    
    /**
     * @ORM\ManyToOne(targetEntity="Lot", inversedBy="certificats", cascade={"persist","refresh"})
     * @ORM\JoinColumn(name="lot_id", referencedColumnName="id")
     * @Gedmo\Versioned
     *
     */
    private $lot;
    
    /**
     * @var String $attribuePar
     * @Gedmo\Versioned
     * @ORM\Column(name="attribue_par", type="string", length=255, nullable=true)
     */
    private $attribuePar;
    
    
    /**
     * @var datetime $dateAttribution
     * @Gedmo\Versioned
     * @ORM\Column(name="date_attribution", type="datetime", nullable=true)
     */
    private $dateAttribution;
    
    /**
     * @var String $motif
     * @Gedmo\Versioned
     * @ORM\Column(name="motif", type="string", length=255, nullable=true)
     */
    private $motif;    
    
    /**
     * @var integer $debut
     *
     * @Assert\NotBlank
     */
    private $debut;
    
    /**
     * @var integer $quantite
     *
     * @Assert\NotBlank
     */
    private $quantite;   

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
    
    function getSerie() {
        $serie = $this->serie;
        while(strlen($serie) < self::digit){
            $serie = "0".$serie;
        }
        return $serie;
    }

    public function getUtilise() {
        return $this->utilise;
    }

    public function getAnnule() {
        return $this->annule;
    }

    public function setSerie($serie) {
        $this->serie = $serie;
    }

    public function setUtilise($utilise) {
        $this->utilise = $utilise;
    }

    public function setAnnule($annule) {
        $this->annule = $annule;
    }

    public function getControlleur() {
        return $this->controlleur;
    }

    public function setControlleur($controlleur) {
        $this->controlleur = $controlleur;
    }
    
    public function getLot() {
        return $this->lot;
    }

    public function setLot($lot) {
        $this->lot = $lot;
    }
    
    public function getImmatriculation(){
        return $this->immatriculation;
    }

    public function setImmatriculation($immatriculation) {
        $this->immatriculation = $immatriculation;
    }

    public function getAttribuePar(){
        return $this->attribuePar;
    }

    public function setAttribuePar($attribuePar) {
        $this->attribuePar = $attribuePar;
    }
    
    public function getDateAttribution(){
        return $this->dateAttribution;
    }

    public function setDateAttribution($dateAttribution) {
        $this->dateAttribution = $dateAttribution;
    }
    
    function getMotif(){
        return $this->motif;
    }

    function setMotif($motif) {
        $this->motif = $motif;
    }
    
    public function getStatus(){
        $stauts = "Actif";
        if($this->annule){
            $stauts = "Annulé";
        }
        if($this->utilise){
            $stauts = "Utilise";
        }
        return $stauts;
    }
    
    function getAnnee() {
        return $this->annee;
    }

    function setAnnee($annee) {
        $this->annee = $annee;
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
        return false ;
    }
    
    public function __toString(){
        return $this->serie;
    }
    
    public function __construct()
    {
        $this->utilise = false;
        $this->annule = false;
    }
    
    function getDebut() {
        return $this->debut;
    }

    function getQuantite() {
        return $this->quantite;
    }

    function setDebut($debut) {
        $this->debut = $debut;
    }

    function setQuantite($quantite) {
        $this->quantite = $quantite;
    }
    
}
