<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Visite
 *
 * @ORM\Table(name="visite")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\VisiteRepository")
 * @UniqueEntity("numeroCertificat")
 * @UniqueEntity("numeroVisite")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Visite
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
     * @var string $observations
     *
     * @ORM\Column(name="observations", type="string", length=255, nullable=true)
     * 
     */
    private $observations;
    
    /**
     * @var integer $statut
     *
     * @ORM\Column(name="statut", type="integer", nullable=false)
     * 
     */
    private $statut;
    
    /**
     * @var string $numeroCertificat
     *
     * @ORM\Column(name="numeroCertificat", type="string", length=255, nullable=true)
     * 
     */
    private $numeroCertificat;
    
    /**
     * @var datetime $date
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     * 
     * 
     */
    private $date;
    
    /**
     * @var datetime $dateValidite
     *
     * @ORM\Column(name="dateValidite", type="datetime", nullable=true)
     * 
     * 
     */
    private $dateValidite;
    
    /**
     * @var boolean $revisite
     *
     * @ORM\Column(name="revisite", type="boolean", nullable=false)
     * 
     */
    private $revisite;
    
    /**
     * @var boolean $contreVisite
     *
     * @ORM\Column(name="contre_visite", type="boolean", nullable=true)
     * 
     */
    private $contreVisite;
    
    /**
    * @ORM\ManyToOne(targetEntity="Visite", inversedBy="revisites", cascade={"persist","refresh"})
    * @ORM\JoinColumn(name="visite_id", referencedColumnName="id")
    * 
    */
    protected $visiteParent;
    
    /**
    * @ORM\ManyToOne(targetEntity="Vehicule", inversedBy="visites", cascade={"persist","refresh"})
    * @ORM\JoinColumn(name="vehicule_id", referencedColumnName="id")
    * @Assert\NotBlank
    */
    protected $vehicule;
    
    /**
    * @ORM\ManyToOne(targetEntity="Penalite", inversedBy="visites", cascade={"persist","refresh"})
    * @ORM\JoinColumn(name="penalite_id", referencedColumnName="id")
    * 
    */
   protected $penalite;
   
   /**
    * @ORM\ManyToOne(targetEntity="Chaine", inversedBy="visites", cascade={"persist","refresh"})
    * @ORM\JoinColumn(name="chaine_id", referencedColumnName="id")
    * 
    */
   protected $chaine;
   
   /**
    * @ORM\OneToOne(targetEntity="Quittance", inversedBy="visite", cascade={"persist","refresh"})
    * @ORM\JoinColumn(name="quittance_id", referencedColumnName="id")
    * 
    */
   protected $quittance;
   
   /**
    * @ORM\ManyToOne(targetEntity="Centre", inversedBy="visites", cascade={"persist","refresh"})
    * @ORM\JoinColumn(name="centre_id", referencedColumnName="id")
    * @Assert\NotBlank
    */
    protected $centre;
   
   //Debut relation Visite Parent a plusieurs revisite
    /**
    * @ORM\OneToMany(targetEntity="Visite", mappedBy="visiteParent", cascade={"persist"})
    */
    protected $revisites;
    
    /**
    * Add revisite
    *
    * @param AppBundle\Entity\Visite $visite
    */
    public function addRevisite(\AppBundle\Entity\Visite $visite)
    {
        $this->revisites[] = $visite;
    }

    /**
     * Get revisites
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRevisites()
    {
        return $this->revisites;
    }

    /**
     * Set revisites
     *
     * @param \Doctrine\Common\Collections\Collection $revisites
     */
    public function setRevisites(\Doctrine\Common\Collections\Collection $revisites)
    {
        $this->revisites = $revisites;
    }
    //Fin relation Visite Parent a plusieurs revisites    
    
    //Debut relation Visite a plusieurs résultat
    /**
    * @ORM\OneToMany(targetEntity="Resultat", mappedBy="visite", cascade={"persist"})
    */
    protected $resultats;
    
    /**
    * Add resultat
    *
    * @param AppBundle\Entity\Resultat $resultat
    */
    public function addResultat(\AppBundle\Entity\Resultat $resultat)
    {
        $this->resultats[] = $resultat;
    }

    /**
     * Get resultats
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getResultats()
    {
        return $this->resultats;
    }

    /**
     * Set resultats
     *
     * @param \Doctrine\Common\Collections\Collection $resultats
     */
    public function setResultats(\Doctrine\Common\Collections\Collection $resultats)
    {
        $this->resultats = $resultats;
    }
    //Fin relation controle a plusieurs resultats

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
    function getCentre() {
        return $this->centre;
    }

    function setCentre($centre) {
        $this->centre = $centre;
    }
    
    function getObservations() {
        return $this->observations;
    }

    function getStatut() {
        return $this->statut;
    }

    function getNumeroCertificat() {
        return $this->numeroCertificat;
    }
    
    function getContreVisite() {
        return $this->contreVisite;
    }

    function setContreVisite($contreVisite) {
        $this->contreVisite = $contreVisite;
    }

    function getDate() {
        return $this->date;
    }

    function getDateValidite() {
        return $this->dateValidite;
    }

    function getRevisite() {
        return $this->revisite;
    }

    function getVisiteParent() {
        return $this->visiteParent;
    }

    function getVehicule() {
        return $this->vehicule;
    }

    function getPenalite() {
        return $this->penalite;
    }

    function getChaine() {
        return $this->chaine;
    }

    function getQuittance() {
        return $this->quittance;
    }

    function setObservations($observations) {
        $this->observations = $observations;
    }

    function setStatut($statut) {
        $this->statut = $statut;
    }

    function setNumeroCertificat($numeroCertificat) {
        $this->numeroCertificat = $numeroCertificat;
    }

    function setDate($date) {
        $this->date = $date;
    }

    function setDateValidite($dateValidite) {
        $this->dateValidite = $dateValidite;
    }

    function setRevisite($revisite) {
        $this->revisite = $revisite;
    }

    function setVisiteParent($visiteParent) {
        $this->visiteParent = $visiteParent;
    }

    function setVehicule($vehicule) {
        $this->vehicule = $vehicule;
    }

    function setPenalite($penalite) {
        $this->penalite = $penalite;
    }

    function setChaine($chaine) {
        $this->chaine = $chaine;
    }

    function setQuittance($quittance) {
        $this->quittance = $quittance;
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
        return $this->quittance->getNumero();
    }

    public function estSupprimable(){
        return $this->revisites == null || count($this->revisites) == 0;
    }
    
    public function __toString(){
        return $this->vehicule->getImmatriculation();
    }
    
    public function __construct()
    {
        $this->revisites = new ArrayCollection();
        $this->contreVisite = false;
    }
    
    public function aiguiller($vehicule, $statut, $chaine, $visiteParent, $centre)
    {
        $this->setVehicule($vehicule);
        $this->setStatut($statut);
        $this->setChaine($chaine);
        $numero = "BAMAKO".\time();
        $this->setNumeroCertificat($numero);
        $this->setDate(new \DateTime());
        if($visiteParent != null){
            $this->setRevisite(1);
            $this->setVisiteParent($visiteParent);
        }else{
            $this->setRevisite(0);
        }
        $this->centre = $centre;
    }

}
