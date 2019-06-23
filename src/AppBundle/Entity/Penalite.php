<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Penalite
 *
 * @ORM\Table(name="penalite")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\PenaliteRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Penalite
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
     * @var integer $dureeMin
     *
     * @ORM\Column(name="dureemin", type="integer", nullable=false)
     * @Assert\NotBlank
     */
    private $dureeMin;
    
    /**
     * @var integer $dureeMax
     *
     * @ORM\Column(name="dureemax", type="integer", nullable=false)
     * @Assert\NotBlank
     */
    private $dureeMax;
    
    /**
     * @var integer $pourcentage
     *
     * @ORM\Column(name="pourcentage", type="integer", nullable=false)
     * @Assert\NotBlank
     */
    private $pourcentage;
    
    //Debut relation Penalite a plusieurs visites
    /**
    * @ORM\OneToMany(targetEntity="Visite", mappedBy="penalite", cascade={"persist"})
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
    //Fin relation penalite a plusieurs visites

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

    function getCode() {
        return $this->code;
    }

    function setLibelle($libelle) {
        $this->libelle = $libelle;
    }

    function setCode($code) {
        $this->code = $code;
    }
    
    function getDureeMin() {
        return $this->dureeMin;
    }

    function getDureeMax() {
        return $this->dureeMax;
    }

    function getPourcentage() {
        return $this->pourcentage;
    }

    function setDureeMin($dureeMin) {
        $this->dureeMin = $dureeMin;
    }

    function setDureeMax($dureeMax) {
        $this->dureeMax = $dureeMax;
    }

    function setPourcentage($pourcentage) {
        $this->pourcentage = $pourcentage;
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
        return $this->droitVisites == null || count($this->droitVisites) == 0 ;
    }
    
    public function __toString(){
        return $this->libelle;
    }
    
    public function __construct()
    {
        
    }
    //fin behavior


}
