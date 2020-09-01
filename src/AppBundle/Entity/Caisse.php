<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Caisse
 *
 * @ORM\Table(name="caisse")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\CaisseRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @UniqueEntity("numero")
 */
class Caisse
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
     * @var integer $numero
     *
     * @ORM\Column(name="numero", type="integer", nullable=false)
     * @Assert\NotBlank
     */
    private $numero;
    
    /**
     * @var boolean $actif
     *
     * @ORM\Column(name="actif", type="boolean", nullable=false)
     * 
     */
    private $actif;
    
    /**
     * @var boolean $aiguillage
     *
     * @ORM\Column(name="aiguillage", type="boolean", nullable=false)
     * 
     */
    private $aiguillage;
    
    /**
     * @var boolean $ouvert
     *
     * @ORM\Column(name="ouvert", type="boolean", nullable=true)
     * 
     */
    private $ouvert;
    
    /**
     * @var float $solde
     *
     * @ORM\Column(name="solde", type="float", nullable=false)
     * 
     */
    private $solde;
    
    /**
     * @var float $soldeInitial
     *
     * @ORM\Column(name="solde_initial", type="float", nullable=true)
     * 
     */
    private $soldeInitial;
    
    /**
     * @var integer $nbvisite
     *
     * @ORM\Column(name="nbvisite", type="integer", nullable=false)
     * 
     */
    private $nbvisite;
    
    /**
     * @var integer $nbrevisite
     *
     * @ORM\Column(name="nbrevisite", type="integer", nullable=false)
     */
    private $nbrevisite;
    
    /**
     * @var float $montantvisite
     *
     * @ORM\Column(name="montantvisite", type="float", nullable=false)
     * 
     */
    private $montantvisite;
    
    /**
     * @var float $montantrevisite
     *
     * @ORM\Column(name="montantrevisite", type="float", nullable=false)
     * 
     */
    private $montantrevisite;
   
   //Debut relation Caisse a plusieurs chaines
    /**
    * @ORM\OneToMany(targetEntity="Chaine", mappedBy="caisse", cascade={"persist"})
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
    //Fin relation caisse a plusieurs chaines
    
    //Debut relation Caisse a plusieurs affectations
    /**
    * @ORM\OneToMany(targetEntity="Affectation", mappedBy="caisse", cascade={"persist"})
    */
    protected $affectations;
    
    /**
    * Add affectation
    *
    * @param AppBundle\Entity\Affectation $affectation
    */
    public function addAffectation(\AppBundle\Entity\Affectation $affectation)
    {
        $this->affectations[] = $affectation;
    }

    /**
     * Get affectations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAffectations()
    {
        return $this->affectations;
    }
    
    /**
     * Get affectation active
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAffectationActive()
    {
        $active = null;
        foreach($this->affectations as $affectation){
            if($affectation->getActif()){
                $active = $affectation;
                break;
            }
        }
        return $active;
    }

    /**
     * Set affectations
     *
     * @param \Doctrine\Common\Collections\Collection $affectations
     */
    public function setAffectations(\Doctrine\Common\Collections\Collection $affectations)
    {
        $this->affectations = $affectations;
    }
    //Fin relation caisse a plusieurs affectations

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

    function getActif() {
        return $this->actif;
    }

    function setNumero($numero) {
        $this->numero = $numero;
    }

    function setActif($actif) {
        $this->actif = $actif;
    }
    
    function getSolde() {
        return $this->solde? $this->solde : 0;
    }

    function setSolde($solde) {
        $this->solde = $solde;
    }
    function getSoldeInitial() {
        return $this->soldeInitial;
    }

    function setSoldeInitial($soldeInitial) {
        $this->soldeInitial = $soldeInitial;
    }
    
    function getOuvert() {
        return $this->ouvert;
    }

    function setOuvert($ouvert) {
        $this->ouvert = $ouvert;
    }
    
    function getNbvisite() {
        return $this->nbvisite;
    }

    function getNbrevisite() {
        return $this->nbrevisite;
    }

    function getMontantvisite() {
        return $this->montantvisite;
    }

    function getMontantrevisite() {
        return $this->montantrevisite;
    }

    function setNbvisite($nbvisite) {
        $this->nbvisite = $nbvisite;
    }

    function setNbrevisite($nbrevisite) {
        $this->nbrevisite = $nbrevisite;
    }

    function setMontantvisite($montantvisite) {
        $this->montantvisite = $montantvisite;
    }

    function setMontantrevisite($montantrevisite) {
        $this->montantrevisite = $montantrevisite;
    }
    
    function getAiguillage() {
        return $this->aiguillage;
    }

    function setAiguillage($aiguillage) {
        $this->aiguillage = $aiguillage;
    }
    
    function cloturer(){
        $this->solde = 0;
        $this->soldeInitial = 0;
        $this->nbvisite = 0;
        $this->nbrevisite = 0;
        $this->montantvisite = 0;
        $this->montantrevisite = 0;
    }
    
    function ajouterVisite($montant){
        $this->nbvisite += 1;
        $this->montantvisite += $montant;
    }
    
    function ajouterRevisite($montant){
        $this->nbrevisite += 1;
        $this->montantrevisite += $montant;
    }
    
    function retirerVisite($montant){
        $this->nbvisite -= 1;
        $this->montantvisite -= $montant;
    }
    
    function retirerRevisite($montant){
        $this->nbrevisite -= 1;
        $this->montantrevisite -= $montant;
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
        return $this->numero;
    }

    public function estSupprimable(){
        return $this->chaines == null || count($this->chaines)==0;
    }
    
    public function __toString(){
        return strval($this->numero);
    }
    
    public function __construct()
    {
        $this->chaines = new ArrayCollection();
        $this->solde = 0;
        $this->soldeInitial = 0;
        $this->ouvert = false;
        $this->actif = false;
        $this->nbrevisite = 0;
        $this->nbvisite = 0;
        $this->montantrevisite = 0;
        $this->montantvisite = 0;
        $this->aiguillage = false;
        
    }
    //fin behavior

    public function encaisser($montant, $revisite){
        $this->solde = $this->getSolde()+$montant;
        if($revisite){
            $this->ajouterRevisite($montant);
        }else{
            $this->ajouterVisite($montant);
        }
    }
    
    public function encaisserQuittance(Quittance $quittance){
        $this->solde = $this->getSolde()+$quittance->getTtc();
        if($quittance->getVisite()->getRevisite()){
            $this->ajouterRevisite($quittance->getTtc());
        }else{
            $this->ajouterVisite($quittance->getTtc());
        }
    }
    
    public function rembourser($montant, $revisite){
        $this->solde = $this->getSolde()-$montant;
        if($revisite){
            $this->retirerRevisite($montant);
        }else{
            $this->retirerVisite($montant);
        }
    }
    
    public function derniereAffectation(){
        $derniere = null;
        if($this->getAffectations() != null && count($this->getAffectations()) > 0){
            foreach($this->getAffectations() as $affectation){
                if($affectation->getActif()){
                    $derniere = $affectation;
                    break;
                }
            }
        }
        return $derniere;
    }

}
