<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Lot
 *
 * @ORM\Table(name="lot")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\LotRepository")
 * @UniqueEntity({"serie", "annee"})
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @Gedmo\Loggable
 */
class Lot
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * 
     */
    private $id;
    
    /** 
     * @ORM\Version 
     * @ORM\Column(type="integer") 
     */
    private $version;
    
    /**
     * @var string $serie
     * @Gedmo\Versioned
     * @ORM\Column(name="serie", type="string", length=255, nullable=false)
     */
    private $serie; 
    
    /**
     * @var integer $anne
     * @Gedmo\Versioned
     * @ORM\Column(name="annee", type="integer", nullable=true)
     */
    private $annee;
    
    /**
     * @var string $quantite
     * @Gedmo\Versioned
     * @ORM\Column(name="quantite", type="integer", length=255, nullable=true)
     */
    private $quantite;
    
    /**
     * @var string $quantiteF
     */
    private $quantiteF;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="Utilisateur", inversedBy="lots", cascade={"persist","refresh"})
     * @ORM\JoinColumn(name="chef_id", referencedColumnName="id")
     * @Gedmo\Versioned
     *
     */
    private $chefCentre;
    
    /**
     * @var string $controlleur
     *
     * @ORM\Column(name="controlleur", type="string", length=255, nullable=true)
     * 
     */
    private $controlleur;
    
    /**
     * @var string $attributeur
     *
     * @ORM\Column(name="attributeur", type="string", length=255, nullable=true)
     * 
     */
    private $attributeur;
    
    /**
     * @var string $attributeurControleur
     *
     * @ORM\Column(name="attributeur_controleur", type="string", length=255, nullable=true)
     * 
     */
    private $attributeurControleur;
    
    /**
     * @var boolean $epuise
     *
     * @ORM\Column(name="epuise", type="boolean", nullable=true)
     * 
     */
    private $epuise;
    
    
    /**
     * @var \DateTime $dateAffectationCentre
     *
     * @ORM\Column(name="date_affectation_centre", type="datetime", nullable=true)
     */
    private $dateAffectationCentre;
    
    /**
     * @var \DateTime $dateAffectationControlleur
     *
     * @ORM\Column(name="date_affectation_controlleur", type="datetime", nullable=true)
     */
    private $dateAffectationControlleur;
    
    /**
     * @var string $nbrAnnule
     * @Gedmo\Versioned
     * @ORM\Column(name="nbr_annule", type="integer", length=255, nullable=true)
     */
    private $nbrAnnule;
    
    //Debut relation Utilisateur a plusieurs certificats
    /**
    * @ORM\OneToMany(targetEntity="Certificat", mappedBy="lot", cascade={"persist"})
    */
    protected $certificats;
    
    /**
    * Add certificat
    *
    * @param AppBundle\Entity\Certificat $certificat
    */
    public function addCertificat(\AppBundle\Entity\Certificat $certificat)
    {
        $this->certificats[] = $certificat;
    }

    /**
     * Get certificats
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCertificats()
    {
        return $this->certificats;
    }

    /**
     * Set certificats
     *
     * @param \Doctrine\Common\Collections\Collection $certificats
     */
    public function setCertificats(\Doctrine\Common\Collections\Collection $certificats)
    {
        $this->certificats = $certificats;
    }
    //Fin relation caisse a plusieurs certificats
    
    private $debut;

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
    
    function getSerie() {
        return $this->serie;
    }

    function setSerie($serie) {
        $this->serie = $serie;
    }
    
    function getDebut() {
        return $this->debut;
    }

    function getQuantite() {
        return $this->quantite;
    }

    function getControlleur() {
        return $this->controlleur;
    }

    function setDebut($debut) {
        $this->debut = $debut;
    }

    function setQuantite($quantite) {
        $this->quantite = $quantite;
    }

    function setControlleur($controlleur) {
        $this->controlleur = $controlleur;
    }
    
    function getChefCentre() {
        return $this->chefCentre;
    }

    function getDateAffectationControlleur() {
        return $this->dateAffectationControlleur;
    }

    function setChefCentre($chefCentre) {
        $this->chefCentre = $chefCentre;
    }

    function setDateAffectationControlleur($dateAffectationControlleur) {
        $this->dateAffectationControlleur = $dateAffectationControlleur;
    }
    
    function getAttributeur() {
        return $this->attributeur;
    }

    function setAttributeur($attributeur) {
        $this->attributeur = $attributeur;
    }
    
    function getAttributeurControleur() {
        return $this->attributeurControleur;
    }

    function getDateAffectationCentre(){
        return $this->dateAffectationCentre;
    }

    function getNbrAnnule() {
        return $this->nbrAnnule;
    }

    function setAttributeurControleur($attributeurControleur) {
        $this->attributeurControleur = $attributeurControleur;
    }

    function setDateAffectationCentre($dateAffectationCentre) {
        $this->dateAffectationCentre = $dateAffectationCentre;
    }

    function setNbrAnnule($nbrAnnule) {
        $this->nbrAnnule = $nbrAnnule;
    }
    
    function getQuantiteF() {
        return $this->quantiteF;
    }

    function setQuantiteF($quantiteF) {
        $this->quantiteF = $quantiteF;
    }
    
    function getEpuise() {
        return $this->epuise;
    }

    function setEpuise($epuise) {
        $this->epuise = $epuise;
    }
    
    function getAnnee() {
        return $this->annee;
    }

    function setAnnee($annee) {
        $this->annee = $annee;
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

    public function estSupprimable(){
        if($this->certificats != null){
            foreach($this->certificats as $certificat){
                if($certificat->getUtilise()){
                    return false;
                }
            }
        }
        return true;
    }
    
    public function __toString(){
        return $this->serie;
    }
    
    public function __construct()
    {
    }
    
    
}
