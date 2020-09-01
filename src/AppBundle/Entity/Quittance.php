<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Quittance
 *
 * @ORM\Table(name="quittance")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\QuittanceRepository")
 * @UniqueEntity("numero")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Quittance
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
     * @var string $numero
     *
     * @ORM\Column(name="numero", type="string", length=255, nullable=false)
     * 
     */
    private $numero;
    
    /**
     * @var float $penalite
     *
     * @ORM\Column(name="penalite", type="float", nullable=false)
     * 
     */
    private $penalite;
    
    /**
     * @var integer $retard
     *
     * @ORM\Column(name="retard", type="integer", nullable=false)
     * 
     */
    private $retard;
    
    /**
     * @var float $montantVisite
     *
     * @ORM\Column(name="montantvisite", type="float", nullable=false)
     * 
     */
    private $montantVisite;
    
    /**
     * @var float $tva
     *
     * @ORM\Column(name="tva", type="float", nullable=false)
     * 
     */
    private $tva;
    
    /**
     * @var float $timbre
     *
     * @ORM\Column(name="timbre", type="float", nullable=false)
     * 
     */
    private $timbre;
    
    /**
     * @var boolean $paye
     *
     * @ORM\Column(name="paye", type="boolean", nullable=false)
     * 
     */
    private $paye;
    
    /**
     * @var boolean $rembourse
     *
     * @ORM\Column(name="rembourse", type="boolean", nullable=false)
     * 
     */
    private $rembourse;
    
    /**
     * @var \DateTime $dateEncaissement
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateEncaissement;
    
    /**
    * @ORM\OneToOne(targetEntity="Visite", mappedBy="quittance", cascade={"persist","refresh"})
    * @ORM\JoinColumn(name="visite_id", referencedColumnName="id")
    * 
    */
   protected $visite;
    

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

    function getPenalite() {
        return $this->penalite;
    }

    function getMontantVisite() {
        return $this->montantVisite ? $this->montantVisite : 0;;
    }

    function setNumero($numero) {
        $this->numero = $numero;
    }

    function setPenalite($penalite) {
        $this->penalite = $penalite;
    }

    function setMontantVisite($montantVisite) {
        $this->montantVisite = $montantVisite;
    }
    function getVisite() {
        return $this->visite;
    }

    function setVisite($visite) {
        $this->visite = $visite;
    }
    
    function getRetard() {
        return $this->retard;
    }

    function setRetard($retard) {
        $this->retard = $retard;
    }
    
    function getPaye() {
        return $this->paye;
    }

    function setPaye($paye) {
        $this->paye = $paye;
    }

    function getDateEncaissement() {
        return $this->dateEncaissement;
    }

    function setDateEncaissement($dateEncaissement) {
        $this->dateEncaissement = $dateEncaissement;
    }
    
    function getRembourse() {
        return $this->rembourse;
    }

    function setRembourse($rembourse) {
        $this->rembourse = $rembourse;
    }
    
    //BEHAVIOR
    /**
     * @var string $creePar
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="Utilisateur", inversedBy="quittances", cascade={"persist","refresh"})
     * @ORM\JoinColumn(name="utilisateur_id", referencedColumnName="id")
     */
    private $caissier;
    
    /**
     * @var string $creePar
     *
     * @ORM\Column(type="string",  nullable=true)
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
        return true;
    }
    
    public function __toString(){
        return $this->libelle;
    }
    
    public function __construct()
    {
        $this->montantVisite = 0;
        $this->paye = 0;
        //$this->users = new ArrayCollection();
    }
    
    public function calculerMontant($derniereVisite = null){
        if($this->visite){
            switch($this->visite->getRevisite()){
                case 0 : return $this->visite->getVehicule()->getTypeVehicule()->getMontantVisite();
                case 1 : return $this->calculerMontantRevisite($derniereVisite);
            }
        }
    }
    
    public function calculerMontantRevisite($derniereVisite){
        if($this->visite){
            switch($this->visite->getVehicule()->getCompteurRevisite()){
                case 0 : case 1 : case 2 : return $this->visite->getVehicule()->getTypeVehicule()->getMontantRevisite();
                case 3 : return 0;
                default : return -9999999999999999;
                }       
        }
    }
    
    public function calculerRetard($derniereVisite){
        if($derniereVisite == null){
            $date = $this->visite->getVehicule()->prochaineVisite();
            $retard = $this->joursDeRetard($date);
        }else{
            if($derniereVisite->getStatut() == 3){
                $retard = 0;
            }else{
                $retard = $this->joursDeRetard($derniereVisite->getDateValidite());
            }
        }
        return $retard;
    }
    
    public function joursDeRetard($dateLimite){
        $date = new \DateTime();
        $ecart = \date_diff($date,$dateLimite, false);
        return ($ecart && $ecart->days > 0) ? $ecart->days : 0;
    }
    
    public function generer($montant, $penalite, $retard){
        $this->setMontantVisite($montant);
        $this->setTva($montant*18/100);
        if($montant > 0){
            $this->setTimbre($this->getVisite()->getVehicule()->getTypeVehicule()->getTimbre());
            if($penalite){
                $this->setPenalite(\ceil($this->getTtc()*$penalite->getPourcentage()/100));
            }else{
                $this->setPenalite(0);
            }
        }
            
        else{
            $this->setTimbre (0);
            $this->setPenalite(0);
        }
        
        $this->retard = $retard;
        $this->rembourse = false;
        $this->setNumero('BKO'.\time());
    }
    
    function getTva() {
        return $this->tva;
    }

    function getTimbre() {
        return $this->timbre;
    }

    function setTva($tva) {
        $this->tva = $tva;
    }

    function setTimbre($timbre) {
        $this->timbre = $timbre;
    }
    
    public function encaisser(){
        $this->setPaye(1);
        $this->getVisite()->setStatut(1);
        $this->setDateEncaissement(new \DateTime());
    }
    
    public function rembourser(){
        $this->setRembourse(true);
        $this->getVisite()->setStatut(5);
        $this->getVisite()->getChaine()->getCaisse()->rembourser($this->getTtc(),$this->getVisite()->getRevisite());        
    }
    
    public function remboursableOu(){
        if($this->getVisite()->getStatut() == 5){
            return -2;
        }
        if($this->visiteDejaFaite()){
            return -1;
        }
        if(!$this->getPaye()){
            return 0;
        }else{
            switch ($this->ouRembourser($this->getDateEncaissement())){
                case 1: return 1;
                case 2 : return 2;
            }
        }
    }
    
    public function controleSolde($solde){
        return $this->getMontantVisite() <= $solde;
    }
    
    public function visiteDejaFaite(){
        return $this->getVisite()->getStatut() > 1;
    }
    
    public function ouRembourser($datePaiement){
        $today = new \DateTime();
        return $datePaiement->format('Y-m-d') == $today->format('Y-m-d') ? 1 : 2;
    }

    public function getTtc(){
        return $this->getMontantVisite() > 0 ? \ceil($this->getMontantVisite()+$this->getTva()+$this->getTimbre()) : 0;
    }
    
    public function initialiserContreVisite(){
        $this->setMontantVisite(0);
        $this->setNumero("Contre Visite_".\time());
        $this->setPaye(1);
        $this->setPenalite(0);
        $this->setRembourse(0);
        $this->setRetard(0);
        $this->setTimbre(0);
        $this->setTva(0);
        $this->setDateEncaissement(new \DateTime());
    }
    
    function getCaissier() {
        return $this->caissier;
    }

    function setCaissier($caissier) {
        $this->caissier = $caissier;
    }


}
