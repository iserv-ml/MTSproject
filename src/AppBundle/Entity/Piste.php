<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Piste
 *
 * @ORM\Table(name="piste")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\PisteRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @UniqueEntity("numero")
 */
class Piste
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
     * @var integer $numero
     *
     * @ORM\Column(name="numero", type="integer", nullable=false)
     * @Assert\NotBlank
     */
    private $numero;
    
    /**
     * @var boolean $actif
     *
     * @ORM\Column(name="actif", type="boolean", nullable=false)
     * @Assert\NotBlank
     */
    private $actif;
   
   //Debut relation Piste a plusieurs chaines
    /**
    * @ORM\OneToMany(targetEntity="Chaine", mappedBy="piste", cascade={"persist"})
    */
    protected $chaines;
    
    /**
    * Add chaine
    *
    * @param AppBundle\Entity\Chaine $chaine
    */
    public function addChaine(\AppBundle\Entity\Chaine $chaine)
    {
        $this->chaines[] = $chaine;
    }

    /**
     * Get chaines
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChaines()
    {
        return $this->chaines;
    }

    /**
     * Set chaines
     *
     * @param \Doctrine\Common\Collections\Collection $chaines
     */
    public function setChaines(\Doctrine\Common\Collections\Collection $chaines)
    {
        $this->chaines = $chaines;
    }
    //Fin relation piste a plusieurs chaines

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
    
    function getNumero() {
        return $this->numero;
    }

    function getActif() {
        return $this->actif;
    }

    function setNumero($numero) {
        $this->numero = $numero;
    }

    function setActif($actif) {
        $this->actif = $actif;
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
        return $this->numero;
    }

    public function estSupprimable(){
        return $this->chaines == null || count($this->chaines)==0;
    }
    
    public function __toString(){
        return $this->numero;
    }
    
    public function __construct()
    {
        $this->chaines = new ArrayCollection();
    }
    //fin behavior


}
