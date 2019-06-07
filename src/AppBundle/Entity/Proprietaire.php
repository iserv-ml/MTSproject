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
     * @var string $nom
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $nom;
    
    /**
     * @var string $prenom
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $prenom;
    
    /**
     * @var string $telephone
     *
     * @ORM\Column(name="telephone", type="string", length=255, nullable=true)
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
     * @var boolean $personneMorale
     *
     * @ORM\Column(name="personneMorale", type="boolean", nullable=false)
     * 
     */
    private $personneMorale;
    
   /**
    * @ORM\ManyToOne(targetEntity="TypePiece", inversedBy="proprietaires", cascade={"persist","refresh"})
    * @ORM\JoinColumn(name="typePiece_id", referencedColumnName="id")
    * @Assert\NotBlank
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
    
    //TRAITEMENT PIECE D'IDENTITE
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $path;
    
    /**
     * @Assert\File(maxSize="600000000")
     */
    private $file;
    
    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path ? null : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return __DIR__.'/../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche
        // le document/image dans la vue.
        return 'uploads/pieces';
    }
    
    private $temp;

    /**
     * Set file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->path)) {
            // store the old name to delete after the update
            $this->temp = $this->path;
            $this->path = null;
        } else {
            $this->path = 'initial';
        }
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->path = $filename.'.'.$this->getFile()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFile()->move($this->getUploadRootDir(), $this->path);

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->getUploadRootDir().'/'.$this->temp);
            // clear the temp image path
            $this->temp = null;
        }
        $this->file = null;
    }

    public function getPath() {
        return $this->path;
    }

    public function setPath($path) {
        $this->path = $path;
    }

}
