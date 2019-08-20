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
 * @ORM\Table(name="sortie_caisse")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\SortieCaisseRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class SortieCaisse
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
     * @var string $type
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $type;
    
    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $description;
    
    /**
     * @var float $montant
     *
     * @ORM\Column(name="montant", type="float", nullable=false)
     * 
     */
    private $montant;
    
    /**
     * @var float $ancienSolde
     *
     * @ORM\Column(name="ancien_solde", type="float", nullable=false)
     * 
     */
    private $ancienSolde;
    
    /**
     * @var float $nouveauSolde
     *
     * @ORM\Column(name="nouveau_solde", type="float", nullable=false)
     * 
     */
    private $nouveauSolde;
    
    /**
     * @var string $commentaire
     *
     * @ORM\Column(name="commentaire", type="string", length=500, nullable=true)
     */
    private $commentaire;
    
    /**
    * @ORM\ManyToOne(targetEntity="Centre", inversedBy="sorties", cascade={"persist","refresh"})
    * @ORM\JoinColumn(name="centre_id", referencedColumnName="id")
    * @Assert\NotBlank
    */
    protected $centre;
   
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
    
    function getType() {
        return $this->type;
    }

    function getMontant() {
        return $this->montant;
    }

    function getCentre() {
        return $this->centre;
    }

    function setType($type) {
        $this->type = $type;
    }

    function setMontant($montant) {
        $this->montant = $montant;
    }

    function setCentre($centre) {
        $this->centre = $centre;
    }
    
    function getAncienSolde() {
        return $this->ancienSolde;
    }

    function getNouveauSolde() {
        return $this->nouveauSolde;
    }

    function getCommentaire() {
        return $this->commentaire;
    }

    function setAncienSolde($ancienSolde) {
        $this->ancienSolde = $ancienSolde;
    }

    function setNouveauSolde($nouveauSolde) {
        $this->nouveauSolde = $nouveauSolde;
    }

    function setCommentaire($commentaire) {
        $this->commentaire = $commentaire;
    }
    
    function getDescription() {
        return $this->description;
    }

    function setDescription($description) {
        $this->description = $description;
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
        return $this->type()." : "/$this->montant;
    }

    public function estSupprimable(){
        return true;
    }
    
    public function __toString(){
        return $this->type()." : "/$this->montant;
    }
    
    public function __construct(Centre $centre = null)
    {
        $this->setCentre($centre);
    }
    
    public function initialiser($montant, $ancienSolde, $type, $description){
        $this->setMontant($montant);
        $this->setAncienSolde($ancienSolde);
        $nouveauSolde = $ancienSolde + $montant;
        $this->setNouveauSolde($nouveauSolde);
        $this->setType($type);
        $this->setDescription($description);
        return $nouveauSolde;
    }
    
    public function ouvertureCaisse($montant, $ancienSolde, $type, $description){
        $this->setMontant($montant);
        $this->setAncienSolde($ancienSolde);
        $nouveauSolde = $ancienSolde - $montant;
        $this->setNouveauSolde($nouveauSolde);
        $this->setType($type);
        $this->setDescription($description);
        return $nouveauSolde;
    }
    
    public function rembourser($montant, $ancienSolde, $type, $description){
        $this->setMontant($montant);
        $this->setAncienSolde($ancienSolde);
        $nouveauSolde = $ancienSolde - $montant;
        $this->setNouveauSolde($nouveauSolde);
        $this->setType($type);
        $this->setDescription($description);
        return $nouveauSolde;
    }
    
    public function traiter(){
        $result = 0;
        switch($this->getType()){
            case "ENTREE" : $result = $this->entree();break;
            case "SORTIE" : $result = $this->sortie();break;
        }
        return $result;        
    }
    
    private function entree(){
        $this->ancienSolde = $this->getCentre()->getSolde();
        $this->nouveauSolde = $this->getAncienSolde() + $this->getMontant();
        $this->getCentre()->setSolde($this->nouveauSolde);
        return 2;        
    }
    
    private function sortie(){
        if($this->getCentre()->getSolde() >= $this->getMontant()){
            $this->ancienSolde = $this->getCentre()->getSolde();
            $this->nouveauSolde = $this->getAncienSolde() - $this->getMontant();
            $this->getCentre()->setSolde($this->nouveauSolde);
            return 2;
        }
        return 1;
    }

}
