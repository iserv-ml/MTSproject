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
     * @var integer $delaiPremiereVisite
     *
     * @ORM\Column(name="delaiPremiereVisite", type="integer", nullable=false)
     * @Assert\NotBlank
     */
    private $delaiPremiereVisite;
    
    //Debut relation Genre a plusieurs typeVehicule
    /**
    * @ORM\OneToMany(targetEntity="TypeVehicule", mappedBy="genre", cascade={"persist"})
    */
    protected $typeVehicules;
    
    /**
    * Add typeVehicule
    *
    * @param AppBundle\Entity\TypeVehicule $typeVehicule
    */
    public function addTypeVehicule(\AppBundle\Entity\TypeVehicule $typeVehicule)
    {
        $this->typeVehicules[] = $typeVehicule;
    }

    /**
     * Get typeVehicules
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTypeVehicules()
    {
        return $this->typeVehicules;
    }

    /**
     * Set typeVehicules
     *
     * @param \Doctrine\Common\Collections\Collection $typeVehicules
     */
    public function setTypeVehicules(\Doctrine\Common\Collections\Collection $typeVehicules)
    {
        $this->typeVehicules = $typeVehicules;
    }
    //Fin relation genre a plusieurs typeVehicule
    
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
    
    //Debut relation Genre a plusieurs controle
    /**
    * @ORM\OneToMany(targetEntity="Controle", mappedBy="genre", cascade={"persist"})
    */
    protected $controles;
    
    /**
    * Add controle
    *
    * @param AppBundle\Entity\Controle $controle
    */
    public function addControle(\AppBundle\Entity\Controle $controle)
    {
        $this->controles[] = $controle;
    }

    /**
     * Get controles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getControles()
    {
        return $this->controles;
    }

    /**
     * Set controles
     *
     * @param \Doctrine\Common\Collections\Collection $controles
     */
    public function setControles(\Doctrine\Common\Collections\Collection $controles)
    {
        $this->controles = $controles;
    }
    //Fin relation Categorie a plusieurs controles

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
    
    function getDelaiPremiereVisite() {
        return $this->delaiPremiereVisite;
    }

    function setDelaiPremiereVisite($delaiPremiereVisite) {
        $this->delaiPremiereVisite = $delaiPremiereVisite;
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
        return $this->typeVehicules == null || count($this->typeVehicules) == 0 || $this->chaines == null || count($this->chaines) == 0 ;
    }
    
    public function __toString(){
        return $this->libelle;
    }
    
    public function __construct()
    {
        $this->typeVehicules = new ArrayCollection();
        $this->chaines = new ArrayCollection();
    }
    //fin behavior

}
