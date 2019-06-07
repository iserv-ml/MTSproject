<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Bien
 *
 */
class BienAutocomplete
{
    
     /**
     * @var string $libelle
     *
     * @ORM\Column(name="libelle", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $libelle;
    
    
    /**
     * @var string $statut
     *
     * @ORM\Column(name="statut", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $statut;
    
    /**
    * @ORM\ManyToOne(targetEntity="TypeBien", inversedBy="biens", cascade={"persist","refresh"})
    * @ORM\JoinColumn(name="type_id", referencedColumnName="id")
    * @Assert\NotBlank
    */
    protected $typeBien;
    
    /**
    * @ORM\ManyToOne(targetEntity="Bien", inversedBy="fils", cascade={"persist","refresh"})
    * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
    * @Assert\NotBlank
    */
    protected $parent;
    
   /**
    * @ORM\ManyToOne(targetEntity="Proprietaire", inversedBy="biens", cascade={"persist","refresh"})
    * @ORM\JoinColumn(name="proprietaire_id", referencedColumnName="id")
    * @Assert\NotBlank
    */
   protected $proprietaire;
    
    //Debut relation bien a plusieurs affaires
    /**
    * @ORM\OneToMany(targetEntity="Affaire", mappedBy="bien", cascade={"persist"})
    */
    protected $affaires;
    
    /**
    * Add affaire
    *
    * @param AppBundle\Entity\Affaire $affaire
    */
    public function addAffaire(\AppBundle\Entity\Affaire $affaire)
    {
        $this->affaires[] = $affaire;
    }

    /**
     * Get affaires
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAffaires()
    {
        return $this->affaires;
    }

    /**
     * Set affaires
     *
     * @param \Doctrine\Common\Collections\Collection $affaires
     */
    public function setAffaires(\Doctrine\Common\Collections\Collection $affaires)
    {
        $this->affaires = $affaires;
    }
    //Fin relation bien a plusieurs affaires
    
    //Debut relation bien a plusieurs fils
    /**
    * @ORM\OneToMany(targetEntity="Bien", mappedBy="paent", cascade={"persist"})
    */
    protected $fils;
    
    /**
    * Add bien
    *
    * @param AppBundle\Entity\Bien $bien
    */
    public function addBien(\AppBundle\Entity\Bien $bien)
    {
        $this->fils[] = $bien;
    }

    /**
     * Get fils
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFils()
    {
        return $this->fils;
    }

    /**
     * Set fils
     *
     * @param \Doctrine\Common\Collections\Collection $fils
     */
    public function setFils(\Doctrine\Common\Collections\Collection $fils)
    {
        $this->fils = $fils;
    }
    //Fin relation bien a plusieurs fils
    
    //Debut relation bien a plusieurs photos
    /**
    * @ORM\OneToMany(targetEntity="Photo", mappedBy="bien", cascade={"persist"})
    */
    protected $photos;
    
    /**
    * Add photo
    *
    * @param AppBundle\Entity\Photo $photo
    */
    public function addPhoto(\AppBundle\Entity\Photo $photo)
    {
        $this->photos[] = $photo;
    }

    /**
     * Get photos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * Set photos
     *
     * @param \Doctrine\Common\Collections\Collection $photos
     */
    public function setPhotos(\Doctrine\Common\Collections\Collection $photos)
    {
        $this->photos = $photos;
    }
    //Fin relation bien a plusieurs photos
    
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
    
    function getStatut() {
        return $this->statut;
    }

    function setStatut($statut) {
        $this->statut = $statut;
    }
    
    function getLibelle() {
        return $this->libelle;
    }

    function setLibelle($libelle) {
        $this->libelle = $libelle;
    }

    function getTypeBien() {
        return $this->typeBien;
    }

    function getProprietaire() {
        return $this->proprietaire;
    }

    function setTypeBien($typeBien) {
        $this->typeBien = $typeBien;
    }

    function setProprietaire($proprietaire) {
        $this->proprietaire = $proprietaire;
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
        return $this->affaires == null || count($this->affaires)==0;
    }
    
    public function __toString(){
        return $this->libelle;
    }
    
    public function __construct()
    {
        $this->users = new ArrayCollection();
    }
    
    function getAutocomplete() {
        return $this->autocomplete;
    }

    function getParent() {
        return $this->parent;
    }

    function setAutocomplete($autocomplete) {
        $this->autocomplete = $autocomplete;
    }

    function setParent($parent) {
        $this->parent = $parent;
    }



}
