<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Chaine
 *
 * @ORM\Table(name="chaine")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ChaineRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Chaine
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
     * @var boolean $actif
     *
     * @ORM\Column(name="actif", type="boolean", nullable=false)
     * 
     */
    private $actif;
    
    /**
     * @var boolean $surRendezVous
     *
     * @ORM\Column(name="surrendezvous", type="boolean", nullable=false)
     * 
     */
    private $surRendezVous;
    
    /**
    * @ORM\ManyToOne(targetEntity="Piste", inversedBy="chaines", cascade={"persist","refresh"})
    * @ORM\JoinColumn(name="piste_id", referencedColumnName="id")
    * @Assert\NotBlank
    */
    protected $piste;
    
    /**
    * @ORM\ManyToOne(targetEntity="Caisse", inversedBy="caisse", cascade={"persist","refresh"})
    * @ORM\JoinColumn(name="caisse_id", referencedColumnName="id")
    * @Assert\NotBlank
    */
    protected $caisse;

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

    function getActif() {
        return $this->actif;
    }

    function getSurRendezVous() {
        return $this->surRendezVous;
    }

    function getPiste() {
        return $this->piste;
    }

    function getCaisse() {
        return $this->caisse;
    }

    function setActif($actif) {
        $this->actif = $actif;
    }

    function setSurRendezVous($surRendezVous) {
        $this->surRendezVous = $surRendezVous;
    }

    function setPiste($piste) {
        $this->piste = $piste;
    }

    function setCaisse($caisse) {
        $this->caisse = $caisse;
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
        return $this->caisse->getNumero()."_".$this->piste->getNumero();
    }

    public function estSupprimable(){
        return true;
    }
    
    public function __toString(){
        return $this->getNomComplet();
    }
    
    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

}
