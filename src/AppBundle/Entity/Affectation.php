<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Affectation
 *
 * @ORM\Table(name="affectation")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\AffectationRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Affectation
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
     * @var datetime $date
     *
     * @ORM\Column(name="date_affectation", type="datetime", length=255, nullable=false)
     * 
     */
    private $date;
    
    /**
    * @ORM\ManyToOne(targetEntity="Utilisateur", inversedBy="affectations", cascade={"persist","refresh"})
    * @ORM\JoinColumn(name="utilisateur_id", referencedColumnName="id")
    * @Assert\NotBlank
    */
    protected $agent;
    
    /**
    * @ORM\ManyToOne(targetEntity="Caisse", inversedBy="affectations", cascade={"persist","refresh"})
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

    function getCaisse() {
        return $this->caisse;
    }

    function setActif($actif) {
        $this->actif = $actif;
    }

    function setCaisse($caisse) {
        $this->caisse = $caisse;
    }
    
    function getDate() {
        return $this->date;
    }

    function getAgent() {
        return $this->agent;
    }

    function setDate($date) {
        $this->date = $date;
    }

    function setAgent($agent) {
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
     * @ORM\Column(name="modifier_par",type="string")
     */
    private $modifierPar;
    
    /**
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;
    
    /**
     * @var \DateTime $dateCreation
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="date_creation",type="datetime")
     */
    private $dateCreation;

    /**
     * @var \DateTime $dateModification
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="date_modification", type="datetime")
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
        return $this->agent->getNomComplet()."_".$this->caisse->getNumero();
    }

    public function estSupprimable(){
        return true;
    }
    
    public function __toString(){
        return $this->agent->getNomComplet()."_".$this->caisse->getNumero();
    }
    
    public function __construct()
    {
        $this->actif = true;
        $this->date = new \DateTime();
    }

}
