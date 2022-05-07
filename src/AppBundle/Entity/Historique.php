<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Quittance
 *
 * @ORM\Table(name="historique")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\HistoryRepository")
 */
class Historique
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
     * @var string $evenement
     *
     * @ORM\Column(name="evenement", type="string", nullable=false)
     * 
     */
    private $evenement;
    
    /**
     * @var string $entite
     *
     * @ORM\Column(name="entite", type="string", nullable=false)
     * 
     */
    private $entite;
    
    /**
     * @var string $ancienneValeur
     *
     * @ORM\Column(name="ancienne_valeur", type="string", nullable=false)
     * 
     */
    private $ancienneValeur;
    
    /**
     * @var string $nouvelleValeur
     *
     * @ORM\Column(name="nouvelle_valeur", type="string", nullable=false)
     * 
     */
    private $nouvelleValeur;   
    
    /**
    * @ORM\ManyToOne(targetEntity="Utilisateur", inversedBy="historiques", cascade={"persist","refresh"})
    * @ORM\JoinColumn(name="utilisateur_id", referencedColumnName="id")
    */
    protected $agent;

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
    
    public function getEvenement() {
        return $this->evenement;
    }

    public function getEntite() {
        return $this->entite;
    }

    public function getAncienneValeur() {
        return $this->ancienneValeur;
    }

    public function getNouvelleValeur() {
        return $this->nouvelleValeur;
    }

    public function setEvenement($evenement) {
        $this->evenement = $evenement;
    }

    public function setEntite($entite) {
        $this->entite = $entite;
    }

    public function setAncienneValeur($ancienneValeur) {
        $this->ancienneValeur = $ancienneValeur;
    }

    public function setNouvelleValeur($nouvelleValeur) {
        $this->nouvelleValeur = $nouvelleValeur;
    } 
    
    public function getAgent() {
        return $this->agent;
    }

    public function setAgent($agent) {
        $this->agent = $agent;
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
        return false;
    }
    
    public function __toString(){
        return $this->creePar;
    }
    
    public function __construct($evenement, $entite, $ancienneValeur, $nouvelleValeur, $agent)
    {
        $this->evenement = $evenement;
        $this->entite = $entite;
        $this->ancienneValeur = $ancienneValeur;
        $this->nouvelleValeur = $nouvelleValeur;
        $this->agent = $agent;               
    }
     
}
