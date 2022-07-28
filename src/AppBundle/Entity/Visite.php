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
     * @var string $controlleur
     *
     * @ORM\Column(name="controlleur", type="string", length=255, nullable=true)
     * 
     */
    private $controlleur;
    
    /**
     * @var datetime $date
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     * 
     * 
     */
    private $date;
    
    /**
     * @var datetime $dateControle
     *
     * @ORM\Column(name="dateControle", type="datetime", nullable=true)
     * 
     * 
     */
    private $dateControle;
    
    /**
     * @var datetime $dateValidite
     *
     * @ORM\Column(name="dateValidite", type="datetime", nullable=true)
     * 
     * 
     */
    private $dateValidite;
    
    /**
     * @var string $immatriculation_v
     *
     * @ORM\Column(name="immatriculation_v", type="string", length=255, nullable=true)
     * 
     */
    private $immatriculation_v;
    
    /**
     * @var boolean $revisite
     *
     * @ORM\Column(name="revisite", type="boolean", nullable=false)
     * 
     */
    private $revisite;
    
    /**
     * @var boolean $succesMaha
     *
     * @ORM\Column(name="succes_maha", type="boolean", nullable=true)
     * 
     */
    private $succesMaha;
    
    /**
     * @var boolean $gratuite
     *
     * @ORM\Column(name="gratuite", type="boolean", nullable=true)
     * 
     */
    private $gratuite;
    
    /**
     * @var boolean $contreVisite
     *
     * @ORM\Column(name="contre_visite", type="boolean", nullable=true)
     * 
     */
    private $contreVisite;
    
    /**
     * @var boolean $contreVisiteVisuelle
     *
     * @ORM\Column(name="contre_visite_visuelle", type="boolean", nullable=true)
     * 
     */
    private $contreVisiteVisuelle;
    
    /**
     * @var boolean $contrevisiteCree
     *
     * @ORM\Column(name="contre_visite_cree", type="boolean", nullable=true)
     * 
     */
    private $contrevisiteCree;
    
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
    
    function getControlleur() {
        return $this->controlleur;
    }

    function setControlleur($controlleur) {
        $this->controlleur = $controlleur;
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
    
    function getSuccesMaha() {
        return ($this->succesMaha != null ) ? $this->succesMaha : false;
    }

    function setSuccesMaha($succesMaha) {
        $this->succesMaha = $succesMaha;
    }
    
    function getDateControle() {
        return $this->dateControle;
    }

    public function getImmatriculation_v() {
        return $this->immatriculation_v;
    }

    public function setImmatriculation_v($immatriculation_v) {
        $this->immatriculation_v = $immatriculation_v;
    }
    
    function setDateControle($dateControle) {
        $this->dateControle = $dateControle;
    }
    
    function getContrevisiteCree() {
        return $this->contrevisiteCree;
    }

    function setContrevisiteCree($contrevisiteCree) {
        $this->contrevisiteCree = $contrevisiteCree;
    }
    
    function getGratuite() {
        return $this->gratuite;
    }

    function setGratuite($gratuite) {
        $this->gratuite = $gratuite;
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
        $this->contreVisiteVisuelle = false;
        $this->contrevisiteCree = false;
    }
    
    public function aiguiller($vehicule, $statut, $chaine, $visiteParent, $centre)
    {
        $this->setVehicule($vehicule);
        $this->setImmatriculation_v($vehicule->getImmatriculation());
        $this->setStatut($statut);
        $this->setChaine($chaine);
        $numero = $centre->getCode().\time();
        $this->setNumeroCertificat($numero);
        $this->setDate(new \DateTime());
        if($visiteParent != null){
            $retour = $this->evaluerDelaiRevisite($visiteParent);
        }else{
            $this->setRevisite(0);
            $retour = 'Aiguillage effectué.';
        }
        $this->centre = $centre;
        return $retour;
    }
    
    private function evaluerDelaiRevisite($visiteParent){
        $date = new \DateTime();
        if($visiteParent->getDateControle() != null){
            $ecart = \date_diff($date,$visiteParent->getDateControle(), false);
            if($ecart && $ecart->days > $this->getVehicule()->getTypeVehicule()->getDelai()){
                $this->setRevisite(0);
                $visiteParent->setObservations('Délai de '.$visiteParent->getVehicule()->getTypeVehicule()->getDelai().' jours dépassé avant la revisite');
                $this->setObservations('Nouvelle visite suite au dépassement du délai de '.$visiteParent->getVehicule()->getTypeVehicule()->getDelai().' jours avant la revisite');
                return $visiteParent->getObservations();
            }else{
                $this->setRevisite(1);
                $this->setVisiteParent($visiteParent);
                if($visiteParent->getSuccesMaha()){
                    $this->setContreVisiteVisuelle(true);
                }
            }
        }else{
            $this->setRevisite(1);
            $this->setVisiteParent($visiteParent);
            if($visiteParent->getSuccesMaha()){
                    $this->setContreVisiteVisuelle(true);
            }
        }
        return 'Aiguillage effectué.';
    }
    
    public function genererFichierMaha(){
        $path = $this->getChaine()->getPiste()->getRepertoire()."CG".DIRECTORY_SEPARATOR.$this->getVehicule()->getImmatriculation().'.CG';
        $contenu = $this->getVehicule()->genererFichierCg();
        try{
            \file_put_contents($path, $contenu);
            return 'Quittance encaissée.';
        } catch (\Exception $exeption){
            return 'Impossible de créer le fichier';
        }
    }
    
    public function lireFichierResultatMaha(){
        $path = $this->getChaine()->getPiste()->getRepertoire()."RES".DIRECTORY_SEPARATOR.$this->getVehicule()->getImmatriculation().'.F';
        try{
            $contenu = fopen($path, 'r');
        } catch (\Exception $exeption){
            echo $exeption->getMessage();
            return null;
        }
        return $contenu;
    }
    
    public function fermerFichierResultatMaha($contenu){
        $path = $this->getChaine()->getPiste()->getRepertoire()."RES".DIRECTORY_SEPARATOR.$this->getVehicule()->getImmatriculation();
        try{
            fclose($contenu);
            rename($path.'.F', $path.'_TRAITE.F');
        } catch (\Exception $exeption){
            echo $exeption->getMessage();
            return null;
        }
    }
    
    public function lireLigneMaha($ligne){
        try{
            return \explode ("=", $ligne);
        } catch (\Exception $exeption){
            return null;
        }
    }
    
    public function traiterLigneMAha(Controle $controle, $resultat){
        if($controle != null){
            
        }else{
            return null;
        }
    }
    
    function getContreVisiteVisuelle() {
        return $this->contreVisiteVisuelle;
    }

    function setContreVisiteVisuelle($contreVisiteVisuelle) {
        $this->contreVisiteVisuelle = $contreVisiteVisuelle;
    }
    
    public function initialiserContreVisite($visuelle, $quittance){
        $this->setContreVisite(true);
        $this->setContreVisiteVisuelle($visuelle);
        $quittance->initialiserContreVisite();
        $this->setQuittance($quittance);
    }
    
    public function initialiserVisiteGratuite($quittance){
        $this->setGratuite(true);
        $quittance->initialiserGratuite();
        $this->setQuittance($quittance);
    }
    
    public function getTypeVisite(){
        if($this->getContrevisiteCree()){
            return "Ignoré";
        }else if($this->getRevisite()){
            return "Revisite";
        }else{
            return "Visite";
        }
    }
    
    public function estSucces(){
        return $this->statut == 2 || $this->statut == 4;
    } 
    
    public function certificatDelivre(){
        return $this->statut == 4;
    }

}
