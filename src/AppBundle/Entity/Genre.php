<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Marque
 *
 * @ORM\Table(name="genre")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\GenreRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @UniqueEntity("code")
 */
class Genre
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
     * @var string $libelle
     *
     * @ORM\Column(name="libelle", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $libelle;
    
    /**
     * @var string $code
     *
     * @ORM\Column(name="code", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $code;
    
       /**
     * @var float $ptacMin
     *
     * @ORM\Column(name="ptacmin", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $ptacMin;
    
    /**
     * @var float $ptacMax
     *
     * @ORM\Column(name="ptacmax", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $ptacMax; 
    
    /**
     * @var float $montantRevisite
     *
     * @ORM\Column(name="montantrevisite", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $montantRevisite;
    
    /**
     * @var integer $delai
     *
     * @ORM\Column(name="delai", type="integer", nullable=false)
     * @Assert\NotBlank
     */
    private $delai;
    
    //Debut relation Genre a plusieurs droitVisite
    /**
    * @ORM\OneToMany(targetEntity="DroitVisite", mappedBy="genre", cascade={"persist"})
    */
    protected $droitVisites;
    
    /**
    * Add droitVisite
    *
    * @param AppBundle\Entity\DroitVisite $droitVisite
    */
    public function addDroitVisite(\AppBundle\Entity\DroitVisite $droitVisite)
    {
        $this->droitVisites[] = $droitVisite;
    }

    /**
     * Get droitVisites
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDroitVisites()
    {
        return $this->droitVisites;
    }

    /**
     * Set droitVisites
     *
     * @param \Doctrine\Common\Collections\Collection $droitVisites
     */
    public function setDroitVisites(\Doctrine\Common\Collections\Collection $droitVisites)
    {
        $this->droitVisites = $droitVisites;
    }
    //Fin relation genre a plusieurs droitVisites
    
    //Debut relation Genre a plusieurs chaines
    /**
    * @ORM\OneToMany(targetEntity="Chaine", mappedBy="genre", cascade={"persist"})
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
    //Fin relation genre a plusieurs chaines

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
    function getMontantRevisite() {
        return $this->montantRevisite;
    }

    function setMontantRevisite($montantRevisite) {
        $this->montantRevisite = $montantRevisite;
    }
    
    function getDelai() {
        return $this->delai;
    }

    function setDelai($delai) {
        $this->delai = $delai;
    }
    
        function getPtacMin() {
        return $this->ptacMin;
    }

    function getPtacMax() {
        return $this->ptacMax;
    }

    function setPtacMin($ptacMin) {
        $this->ptacMin = $ptacMin;
    }

    function setPtacMax($ptacMax) {
        $this->ptacMax = $ptacMax;
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
        return $this->droitVisites == null || count($this->droitVisites) == 0 || $this->chaines == null || count($this->chaines) == 0 ;
    }
    
    public function __toString(){
        return $this->libelle;
    }
    
    public function __construct()
    {
        $this->droitVisites = new ArrayCollection();
    }
    //fin behavior


}
