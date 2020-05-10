<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Proprietaire
 *
 * @ORM\Table(name="proprietaire")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ProprietaireRepository")
 * @UniqueEntity("numpiece")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @ORM\HasLifecycleCallbacks
 */
class Proprietaire
{
    /**
    * @var integer
    * @ORM\Column(name="id", type="guid")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="UUID")
    */
    private $id;
    
    /** 
     * @ORM\Version 
     * @ORM\Column(type="integer") 
     */
    private $version;
    
    /**
     * @var string $numpiece
     *
     * @ORM\Column(name="numpiece", type="string", length=255, nullable=true)
     * 
     */
    private $numpiece;
    
    /**
     * @var string $idOttosys
     *
     * @ORM\Column(name="id_ottosy", type="string", length=255, nullable=true)
     * 
     */
    private $idOttosys;
    
    /**
     * @var string $nom
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $nom;
    
    /**
     * @var string $prenom
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
     */
    private $prenom;
    
    /**
     * @var string $telephone
     *
     * @ORM\Column(name="telephone", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $telephone;
    
    /**
     * @var string $autreTelephone
     *
     * @ORM\Column(name="autreTelephone", type="string", length=255, nullable=true)
     * 
     */
    private $autreTelephone;
    
    /**
     * @var string $adresse
     *
     * @ORM\Column(name="adresse", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $adresse;
    
    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     * 
     */
    private $email;
    
    /**
     * @var string $fonction
     *
     * @ORM\Column(name="fonction", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $fonction;
    
    /**
     * @var boolean $personneMorale
     *
     * @ORM\Column(name="personneMorale", type="boolean", nullable=false)
     * 
     */
    private $personneMorale;
    
   /**
    * @ORM\ManyToOne(targetEntity="TypePiece", inversedBy="proprietaires", cascade={"persist","refresh"})
    * @ORM\JoinColumn(name="typePiece_id", referencedColumnName="id")
    */
   protected $typePiece;
    
    //Debut relation Proprietaire a plusieurs vehicules
    /**
    * @ORM\OneToMany(targetEntity="Vehicule", mappedBy="proprietaire", cascade={"persist"})
    */
    protected $vehicules;
    
    /**
    * Add vehicule
    *
    * @param AppBundle\Entity\Vehicule $vehicule
    */
    public function addVehicule(\AppBundle\Entity\Vehicule $vehicule)
    {
        $this->vehicules[] = $vehicule;
    }

    /**
     * Get vehicules
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVehicules()
    {
        return $this->vehicules;
    }

    /**
     * Set vehicules
     *
     * @param \Doctrine\Common\Collections\Collection $vehicules
     */
    public function setVehicules(\Doctrine\Common\Collections\Collection $vehicules)
    {
        $this->vehicules = $vehicules;
    }
    //Fin relation proprietaire a plusieurs vehicules

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
    
    
    function getNumpiece() {
        return $this->numpiece;
    }

    function getNom() {
        return $this->nom;
    }

    function getPrenom() {
        return $this->prenom;
    }

    function getTelephone() {
        return $this->telephone;
    }

    function getAdresse() {
        return $this->adresse;
    }

    function getEmail() {
        return $this->email;
    }

    function getTypePiece() {
        return $this->typePiece;
    }

    function setNumpiece($numpiece) {
        $this->numpiece = $numpiece;
    }

    function setNom($nom) {
        $this->nom = $nom;
    }

    function setPrenom($prenom) {
        $this->prenom = $prenom;
    }

    function setTelephone($telephone) {
        $this->telephone = $telephone;
    }

    function setAdresse($adresse) {
        $this->adresse = $adresse;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setTypePiece($typePiece) {
        $this->typePiece = $typePiece;
    }
    
    function getFonction() {
        return $this->fonction;
    }

    function setFonction($fonction) {
        $this->fonction = $fonction;
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
        return ($this->personneMorale == 0) ? $this->nom." ".$this->prenom : $this->nom;
    }

    public function estSupprimable(){
        return $this->vehicules == null || count($this->vehicules)==0;
    }
    
    public function __toString(){
        return $this->getNomComplet();
    }
    
    public function __construct()
    {
        $this->vehicules = new ArrayCollection();
    }
    
    function getTypePersonne() {
        return $this->typePersonne;
    }

    function setTypePersonne($typePersonne) {
        $this->typePersonne = $typePersonne;
    }

    function getAutreTelephone() {
        return $this->autreTelephone;
    }

    function setAutreTelephone($autreTelephone) {
        $this->autreTelephone = $autreTelephone;
    }

    function getPersonneMorale() {
        return $this->personneMorale;
    }

    function setPersonneMorale($personneMorale) {
        $this->personneMorale = $personneMorale;
    }
    
    function getIdOttosys() {
        return $this->idOttosys;
    }

    function setIdOttosys($idOttosys) {
        $this->idOttosys = $idOttosys;
    }

}
