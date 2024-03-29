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
     * 
     */
    private $actif;
    
    /**
     * @var string $repertoire
     *
     * @ORM\Column(name="repertoire", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $repertoire;
   
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
    
    //Debut relation Piste a plusieurs affectations
    /**
    * @ORM\OneToMany(targetEntity="AffectationPiste", mappedBy="piste", cascade={"persist"})
    */
    protected $affectations;
    
    /**
    * Add affectation
    *
    * @param AppBundle\Entity\AffectationPiste $affectation
    */
    public function addAffectation(\AppBundle\Entity\AffectationPiste $affectation)
    {
        $this->affectations[] = $affectation;
    }

    /**
     * Get affectations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAffectations()
    {
        return $this->affectations;
    }
    
    /**
     * Get affectation active
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAffectationActive()
    {
        $active = null;
        foreach($this->affectations as $affectation){
            if($affectation->getActif()){
                $active = $affectation;
                break;
            }
        }
        return $active;
    }
    
    /**
     * Get affectation active
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAffectationsActives()
    {
        $affectations = array();
        foreach($this->affectations as $affectation){
            if($affectation->getActif()){
                $affectations[] = $affectation;
            }
        }
        return $affectations;
    }
    
    public function chaineActiveOuAffectation(){
        return ($this->affectationActive() || $this->chaineActive());
    }
    public function chaineActive(){
        foreach($this->chaines as $chaine){
            if($chaine->getActif()){
                return true;
            }
        }
        return false;
    }
    
    public function affectationActive(){
        foreach($this->affectations as $affectation){
            if($affectation->getActif()){
                return true;;
            }
        }
        return false;
    }

    /**
     * Set affectations
     *
     * @param \Doctrine\Common\Collections\Collection $affectations
     */
    public function setAffectations(\Doctrine\Common\Collections\Collection $affectations)
    {
        $this->affectations = $affectations;
    }
    //Fin relation piste a plusieurs affectations

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
    
    function getRepertoire() {
        return $this->repertoire;
    }

    function setRepertoire($repertoire) {
        $this->repertoire = $repertoire;
    }
    
    public function getNomComplet(){
        return $this->numero;
    }

    public function estSupprimable(){
        return $this->chaines == null || count($this->chaines)==0;
    }
    
    public function __toString(){
        return strval($this->numero);
    }
    
    public function __construct()
    {
        $this->chaines = new ArrayCollection();
    }
    //fin behavior


}
