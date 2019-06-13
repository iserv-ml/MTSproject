<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Resultat
 *
 * @ORM\Table(name="resultat")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ResultatRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Resultat
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
     * @var string $commentaire
     *
     * @ORM\Column(name="commentaire", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $commentaire;
    
    /**
     * @var boolean $succes
     *
     * @ORM\Column(name="succes", type="boolean", nullable=false)
     * 
     */
    private $succes;
    
    
    /**
     * @var datetime $date
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     * @Assert\NotBlank
     */
    private $date;

    
    /**
    * @ORM\ManyToOne(targetEntity="Controle", inversedBy="resultats", cascade={"persist","refresh"})
    * @ORM\JoinColumn(name="controle_id", referencedColumnName="id")
    * @Assert\NotBlank
    */
    protected $controle;

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
    function getCommentaire() {
        return $this->commentaire;
    }

    function getSucces() {
        return $this->succes;
    }

    function getDate() {
        return $this->date;
    }

    function getControle() {
        return $this->controle;
    }

    function setCommentaire($commentaire) {
        $this->commentaire = $commentaire;
    }

    function setSucces($succes) {
        $this->succes = $succes;
    }

    function setDate(datetime $date) {
        $this->date = $date;
    }

    function setControle($controle) {
        $this->controle = $controle;
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
