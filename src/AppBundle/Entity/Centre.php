<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Centre
 *
 * @ORM\Table(name="centre")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\CentreRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Centre
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
     * @var string $code
     *
     * @ORM\Column(name="code", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $code;
    
    /**
     * @var string $libelle
     *
     * @ORM\Column(name="libelle", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $libelle;
    
    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $description;
    
    /**
     * @var string $adresse
     *
     * @ORM\Column(name="adresse", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $adresse;
    
    /**
     * @var string $telephone
     *
     * @ORM\Column(name="telephone", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $telephone;
    
    
    /**
     * @var boolean $etat
     *
     * @ORM\Column(name="etat", type="boolean", nullable=false)
     * 
     * 
     */
    private $etat;
    
    /**
     * @var float $solde
     *
     * @ORM\Column(name="solde", type="float", nullable=true)
     * 
     */
    private $solde;
    
    /**
     * @var integer $carteVierge
     *
     * @ORM\Column(name="carte_vierge", type="integer", nullable=true)
     */
    private $carteVierge;
    
    /**
     * @var integer $carteViergeOuverture
     *
     * @ORM\Column(name="carte_vierge_ouverture", type="integer", nullable=true)
     */
    private $carteViergeOuverture;
    
    /**
     * @var float $anaser
     *
     * @ORM\Column(name="anaser", type="float", nullable=true)
     * 
     */
    private $anaser;
    
    /**
     * @var string $repertoire
     *
     * @ORM\Column(name="repertoire", type="string", length=255, nullable=true)
     */
    private $repertoire;
    
    /**
     * @var string $ftpServer
     *
     * @ORM\Column(name="ftp_server", type="string", length=255, nullable=true)
     */
    private $ftpServer;
    
    /**
     * @var string $ftpUsername
     *
     * @ORM\Column(name="ftp_username", type="string", length=255, nullable=true)
     */
    private $ftpUsername;
    
    /**
     * @var string $ftpUserpass
     *
     * @ORM\Column(name="ftp_userpass", type="string", length=255, nullable=true)
     */
    private $ftpUserpass;
   
   //Debut relation Centre a plusieurs visites
    /**
    * @ORM\OneToMany(targetEntity="Visite", mappedBy="centre", cascade={"persist"})
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
    //Fin relation centre a plusieurs visites
    
    //Debut relation Centre a plusieurs sorties de caisse
    /**
    * @ORM\OneToMany(targetEntity="SortieCaisse", mappedBy="centre", cascade={"persist"})
    */
    protected $sorties;
    
    /**
    * Add sortie
    *
    * @param AppBundle\Entity\SortieCaisse $sortie
    */
    public function addSortie(\AppBundle\Entity\SortieCaisse $sortie)
    {
        $this->sorties[] = $sortie;
    }

    /**
     * Get sorties
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSorties()
    {
        return $this->sorties;
    }

    /**
     * Set sorties
     *
     * @param \Doctrine\Common\Collections\Collection $sorties
     */
    public function setSorties(\Doctrine\Common\Collections\Collection $sorties)
    {
        $this->sorties = $sorties;
    }
    //Fin relation centre a plusieurs sorties de caisse

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
    
    function getSolde() {
        return $this->solde? $this->solde : 0;
    }

    function setSolde($solde) {
        $this->solde = $solde;
    }
    function getCode() {
        return $this->code;
    }

    function getLibelle() {
        return $this->libelle;
    }

    function getDescription() {
        return $this->description;
    }

    function getAdresse() {
        return $this->adresse;
    }

    function getTelephone() {
        return $this->telephone;
    }

    function getEtat() {
        return $this->etat;
    }

    function setCode($code) {
        $this->code = $code;
    }

    function setLibelle($libelle) {
        $this->libelle = $libelle;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    function setAdresse($adresse) {
        $this->adresse = $adresse;
    }

    function setTelephone($telephone) {
        $this->telephone = $telephone;
    }

    function setEtat($etat) {
        $this->etat = $etat;
    }
    
    function getCarteVierge() {
        return $this->carteVierge;
    }

    function setCarteVierge($carteVierge) {
        $this->carteVierge = $carteVierge;
    }
    
    function getCarteViergeOuverture() {
        return $this->carteViergeOuverture;
    }

    function setCarteViergeOuverture($carteViergeOuverture) {
        $this->carteViergeOuverture = $carteViergeOuverture;
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
        return $this->visites == null || count($this->visites)==0;
    }
    
    public function __toString(){
        return $this->libelle;
    }
    
    public function __construct()
    {
        $this->visites = new ArrayCollection();
        $this->etat = false;
        $this->solde = 0;
    }
    //fin behavior

    public function encaisser(Caisse $caisse){
        $sortie = new SortieCaisse($this);
        $this->solde = $sortie->initialiser($caisse->getSolde()+$caisse->getSoldeInitial(), $this->getSolde(), "ENTREE", "Fermeture de la caisse N° ".$caisse->getNumero());
        return $sortie;
    }
    
    public function ouvertureCaisse(Caisse $caisse){
        if($this->solde >= $caisse->getSoldeInitial()){
            $caisse->setOuvert(true);
            $sortie = new \AppBundle\Entity\SortieCaisse();
            $this->solde = $sortie->ouvertureCaisse($caisse->getSoldeInitial(), $this->getSolde(),"SORTIE", "Ouverture de la caisse N°".$caisse->getNumero());
            $sortie->setCentre($this);
            return $sortie;
        }
        return null;
    }
    
    public function decaisser($montant){
        $this->solde = $this->getSolde()-$montant;
    }
    
    public function decrementerCarteVierge(){
        $this->carteVierge -= 1; 
    }
    
    public function rembourser(Quittance $quittance, $login=null){
        $quittance->setRembourse(true);
        $quittance->getVisite()->setStatut(5);
        $quittance->setRemboursePar($login);
        $sortie = new SortieCaisse($this);
        $this->solde = $sortie->rembourser($quittance->getMontantVisite(), $this->getSolde(), "SORTIE", "Remboursement de la quittance N° ".$quittance->getNumero());
        return $sortie;
    }
    
    public function estOuvert(){
        return $this->getEtat();
    }
    
    function getAnaser() {
        return $this->anaser;
    }

    function setAnaser($anaser) {
        $this->anaser = $anaser;
    }
    
    public function getFtpServer() {
        return $this->ftpServer;
    }

    public function getFtpUsername() {
        return $this->ftpUsername;
    }

    public function getFtpUserpass() {
        return $this->ftpUserpass;
    }


    public function setFtpServer($ftpServer) {
        $this->ftpServer = $ftpServer;
    }

    public function setFtpUsername($ftpUsername) {
        $this->ftpUsername = $ftpUsername;
    }

    public function setFtpUserpass($ftpUserpass) {
        $this->ftpUserpass = $ftpUserpass;
    }
    
    public function getRepertoire() {
        return $this->repertoire;
    }

    public function setRepertoire($repertoire) {
        $this->repertoire = $repertoire;
    }

}
