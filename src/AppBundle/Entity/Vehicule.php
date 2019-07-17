<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Vehicule
 *
 * @ORM\Table(name="vehicule")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\VehiculeRepository")
 * @UniqueEntity("chassis")
 * @UniqueEntity("carteGrise")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Vehicule
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
     * @var string $chassis
     *
     * @ORM\Column(name="chassis", type="string", length=255, nullable=true)
     * 
     */
    private $chassis;
    
    /**
     * @var string $carteGrise
     *
     * @ORM\Column(name="carteGrise", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $carteGrise;
    
    /**
     * @var string $commentaire
     *
     * @ORM\Column(name="commentaire", type="string", length=500, nullable=true)
     * 
     */
    private $commentaire;
    
    /**
     * @var string $immatriculation
     *
     * @ORM\Column(name="immatriculation", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 10,
     *      max = 10,
     *      minMessage = "Veuillez saisir une immatriculation valide de 8 charactères",
     *      maxMessage = "Veuillez saisir une immatriculation valide de 8 charactères"
     * )
     */
    private $immatriculation;
    
    /**
     * @var date $dateCarteGrise
     *
     * @ORM\Column(name="dateCarteGrise", type="string", nullable=false)
     * @Assert\NotBlank
     * 
     */
    private $dateCarteGrise;
    
    /**
     * @var string $dateValidite
     *
     * @ORM\Column(name="dateValidite", type="string", nullable=false)
     * @Assert\NotBlank
     * 
     */
    private $dateValidite;
    
    /**
     * @var string $energie
     *
     * @ORM\Column(name="energie", type="string", nullable=false)
     * 
     */
    private $energie;
    
    /**
     * @var integer $pv
     *
     * @ORM\Column(name="pv", type="integer", nullable=false)
     * 
     */
    private $pv;
    
    /**
     * @var integer $cu
     *
     * @ORM\Column(name="cu", type="integer", nullable=false)
     * 
     */
    private $cu;
    
    /**
     * @var integer $puissanceReelle
     *
     * @ORM\Column(name="puissanceReelle", type="integer", nullable=true)
     * 
     */
    private $puissanceReelle;
    
    /**
     * @var integer $capacite
     *
     * @ORM\Column(name="capacite", type="integer", nullable=true)
     * 
     */
    private $capacite;
    
    /**
     * @var string $moteur
     *
     * @ORM\Column(name="moteur", type="string", nullable=true)
     *
     * 
     */
    private $moteur;
    
    /**
     * @var string $typeChassis
     *
     * @ORM\Column(name="typeChassis", type="string", nullable=false)
     * @Assert\NotBlank
     * 
     */
    private $typeChassis;
    
    /**
     * @var string $immatriculationPrecedent
     *
     * @ORM\Column(name="immatriculationPrecedent", type="string", nullable=true)
     * 
     * 
     */
    private $immatriculationPrecedent;
    
    /**
     * @var string $dateImmatriculationPrecedent
     *
     * @ORM\Column(name="dateImmatriculationPrecedent", type="string", nullable=true)
     * 
     */
    private $dateImmatriculationPrecedent;
    
    /**
     * @var string $typeCarte
     *
     * @ORM\Column(name="typeCarte", type="string", nullable=false)
     * 
     */
    private $typeCarte;    
    
    /**
     * @var datetime $dateMiseCirculation
     *
     * @ORM\Column(name="dateMiseCirculation", type="string", nullable=false)
     * @Assert\NotBlank
     * 
     */
    private $dateMiseCirculation;
    
    /**
     * @var integer $ptac
     *
     * @ORM\Column(name="ptac", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $ptac;
    
    /**
     * @var integer $place
     *
     * @ORM\Column(name="place", type="integer", nullable=false)
     * @Assert\NotBlank
     */
    private $place;
    
    /**
     * @var integer $puissance
     *
     * @ORM\Column(name="puissance", type="integer", nullable=false)
     * @Assert\NotBlank
     */
    private $puissance;
    
    /**
     * @var integer $kilometrage
     *
     * @ORM\Column(name="kilometrage", type="integer", nullable=true)
     * 
     */
    private $kilometrage;
    
    /**
     * @var string $couleur
     *
     * @ORM\Column(name="couleur", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $couleur;
    
    /**
    * @ORM\ManyToOne(targetEntity="Modele", inversedBy="vehicules", cascade={"persist","refresh"})
    * @ORM\JoinColumn(name="modele_id", referencedColumnName="id")
    * 
    */
    protected $modele;
    
    //Debut relation Vehicule a plusieurs visites
    /**
    * @ORM\OneToMany(targetEntity="Visite", mappedBy="vehicule", cascade={"persist"})
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
    //Fin relation vehicule a plusieurs visites
    
    protected $modeleAjax;
    protected $modeleId;
    
    protected $carrosserieAjax;
    protected $carrosserieId;
    
    protected $genreAjax;
    protected $genreId;

    protected $usageAjax;
    protected $usageId;  
    
    protected $proprietaireAjax;
    protected $proprietaireId;
    
    /**
    * @ORM\ManyToOne(targetEntity="Proprietaire", inversedBy="vehicules", cascade={"persist","refresh"})
    * @ORM\JoinColumn(name="proprietaire_id", referencedColumnName="id")
    * 
    */
    protected $proprietaire;
    
    /**
    * @ORM\ManyToOne(targetEntity="TypeVehicule", inversedBy="vehicules", cascade={"persist","refresh"})
    * @ORM\JoinColumn(name="type_vehicule_id", referencedColumnName="id")
    * 
    */
    protected $typeVehicule;
    
    /**
    * @ORM\ManyToOne(targetEntity="TypeImmatriculation", inversedBy="vehicules", cascade={"persist","refresh"})
    * @ORM\JoinColumn(name="type_immatriculation_id", referencedColumnName="id")
    * @Assert\NotBlank
    */
   protected $typeImmatriculation;

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
    
    function getChassis() {
        return $this->chassis;
    }

    function getCarteGrise() {
        return $this->carteGrise;
    }

    function getDateCarteGrise() {
        return $this->dateCarteGrise;
    }

    function getDateMiseCirculation() {
        return $this->dateMiseCirculation;
    }

    function getPtac() {
        return $this->ptac;
    }

    function getPlace() {
        return $this->place;
    }

    function getPuissance() {
        return $this->puissance;
    }

    function getKilometrage() {
        return $this->kilometrage;
    }

    function getCouleur() {
        return $this->couleur;
    }

    function getModele() {
        return $this->modele;
    }

    function setChassis($chassis) {
        $this->chassis = $chassis;
    }

    function setCarteGrise($carteGrise) {
        $this->carteGrise = $carteGrise;
    }

    function setDateCarteGrise($dateCarteGrise) {
        $this->dateCarteGrise = $dateCarteGrise;
    }

    function setDateMiseCirculation($dateMiseCirculation) {
        $this->dateMiseCirculation = $dateMiseCirculation;
    }

    function setPtac($ptac) {
        $this->ptac = $ptac;
    }

    function setPlace($place) {
        $this->place = $place;
    }

    function setPuissance($puissance) {
        $this->puissance = $puissance;
    }

    function setKilometrage($kilometrage) {
        $this->kilometrage = $kilometrage;
    }

    function setCouleur($couleur) {
        $this->couleur = $couleur;
    }

    function setModele($modele) {
        $this->modele = $modele;
    }
    
    function getProprietaire() {
        return $this->proprietaire;
    }

    function setProprietaire($proprietaire) {
        $this->proprietaire = $proprietaire;
    }
  
    function getImmatriculation() {
        return $this->immatriculation;
    }

    function setImmatriculation($immatriculation) {
        $this->immatriculation = $immatriculation;
    }
    function getTypeVehicule() {
        return $this->typeVehicule;
    }

    function getTypeImmatriculation() {
        return $this->typeImmatriculation;
    }

    function setTypeVehicule($typeVehicule) {
        $this->typeVehicule = $typeVehicule;
    }

    function setTypeImmatriculation($typeImmatriculation) {
        $this->typeImmatriculation = $typeImmatriculation;
    }
    
    function getModeleAjax() {
        return $this->modeleAjax;
    }

    function getModeleId() {
        return $this->modeleId;
    }

    function setModeleAjax($modeleAjax) {
        $this->modeleAjax = $modeleAjax;
    }

    function setModeleId($modeleId) {
        $this->modeleId = $modeleId;
    }
    function getCommentaire() {
        return $this->commentaire;
    }

    function setCommentaire($commentaire) {
        $this->commentaire = $commentaire;
    }
    function getDateValidite() {
        return $this->dateValidite;
    }

    function getEnergie() {
        return $this->energie;
    }

    function getPv() {
        return $this->pv;
    }

    function getCu() {
        return $this->cu;
    }

    function getPuissanceReelle() {
        return $this->puissanceReelle;
    }

    function getCapacite() {
        return $this->capacite;
    }

    function getMoteur() {
        return $this->moteur;
    }

    function getTypeChassis() {
        return $this->typeChassis;
    }

    function getImmatriculationPrecedent() {
        return $this->immatriculationPrecedent;
    }

    function getDateImmatriculationPrecedent() {
        return $this->dateImmatriculationPrecedent;
    }

    function getTypeCarte() {
        return $this->typeCarte;
    }

    function setDateValidite($dateValidite) {
        $this->dateValidite = $dateValidite;
    }

    function setEnergie($energie) {
        $this->energie = $energie;
    }

    function setPv($pv) {
        $this->pv = $pv;
    }

    function setCu($cu) {
        $this->cu = $cu;
    }

    function setPuissanceReelle($puissanceReelle) {
        $this->puissanceReelle = $puissanceReelle;
    }

    function setCapacite($capacite) {
        $this->capacite = $capacite;
    }

    function setMoteur($moteur) {
        $this->moteur = $moteur;
    }

    function setTypeChassis($typeChassis) {
        $this->typeChassis = $typeChassis;
    }

    function setImmatriculationPrecedent($immatriculationPrecedent) {
        $this->immatriculationPrecedent = $immatriculationPrecedent;
    }

    function setDateImmatriculationPrecedent($dateImmatriculationPrecedent) {
        $this->dateImmatriculationPrecedent = $dateImmatriculationPrecedent;
    }

    function setTypeCarte($typeCarte) {
        $this->typeCarte = $typeCarte;
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
    
    //TRAITEMENT PIECE D'IDENTITE
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $path;
    
    /**
     * @Assert\File(maxSize="10485760")
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
        return 'uploads/vehicules';
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
