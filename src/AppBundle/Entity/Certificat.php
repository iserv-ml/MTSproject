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
 * @UniqueEntity("serie")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @Gedmo\Loggable
 */
class Certificat
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
     * @var integer $serie
     * @Gedmo\Versioned
     * @ORM\Column(name="serie", type="integer", length=255, nullable=false)
     */
    private $serie;
    
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
        return $this->serie;
    }

    function getUtilise() {
        return $this->utilise;
    }

    function getAnnule() {
        return $this->annule;
    }

    function setSerie($serie) {
        $this->serie = $serie;
    }

    function setUtilise($utilise) {
        $this->utilise = $utilise;
    }

    function setAnnule($annule) {
        $this->annule = $annule;
    }

    function getControlleur() {
        return $this->controlleur;
    }

    function setControlleur($controlleur) {
        $this->controlleur = $controlleur;
    }
    
    function getLot() {
        return $this->lot;
    }

    function setLot($lot) {
        $this->lot = $lot;
    }
    
    function getImmatriculation(){
        return $this->immatriculation;
    }

    function setImmatriculation($immatriculation) {
        $this->immatriculation = $immatriculation;
    }

    function getAttribuePar(){
        return $this->attribuePar;
    }

    function setAttribuePar($attribuePar) {
        $this->attribuePar = $attribuePar;
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
