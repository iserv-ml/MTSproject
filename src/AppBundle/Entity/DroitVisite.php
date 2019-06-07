<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * DroitVisite
 *
 * @ORM\Table(name="droitvisite")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\DroitVisiteRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class DroitVisite
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
     * @var float $ptacMin
     *
     * @ORM\Column(name="ptacmin", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $ptacMin;
    
    /**
     * @var float $ptacMax
     *
     * @ORM\Column(name="ptacmax", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $ptacMax;
    
    /**
     * @var float $montant
     *
     * @ORM\Column(name="montant", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $montant;
    
    /**
     * @var float $timbre
     *
     * @ORM\Column(name="timbre", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $timbre;
    
    /**
     * @var float $anasser
     *
     * @ORM\Column(name="anasser", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $anasser;
    
    /**
    * @ORM\ManyToOne(targetEntity="Genre", inversedBy="droitVisites", cascade={"persist","refresh"})
    * @ORM\JoinColumn(name="genre_id", referencedColumnName="id")
    * @Assert\NotBlank
    */
    protected $genre;
    
    /**
    * @ORM\ManyToOne(targetEntity="Usage", inversedBy="droitVisites", cascade={"persist","refresh"})
    * @ORM\JoinColumn(name="usage_id", referencedColumnName="id")
    * @Assert\NotBlank
    */
    protected $usage;
    
    /**
    * @ORM\ManyToOne(targetEntity="Carrosserie", inversedBy="droitVisites", cascade={"persist","refresh"})
    * @ORM\JoinColumn(name="carrosserie_id", referencedColumnName="id")
    * @Assert\NotBlank
    */
    protected $carrosserie;

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
    
    function getPtacMin() {
        return $this->ptacMin;
    }

    function getPtacMax() {
        return $this->ptacMax;
    }

    function getMontant() {
        return $this->montant;
    }

    function getTimbre() {
        return $this->timbre;
    }

    function getAnasser() {
        return $this->anasser;
    }

    function getGenre() {
        return $this->genre;
    }

    function getUsage() {
        return $this->usage;
    }

    function getCarrosserie() {
        return $this->carrosserie;
    }

    function setPtacMin($ptacMin) {
        $this->ptacMin = $ptacMin;
    }

    function setPtacMax($ptacMax) {
        $this->ptacMax = $ptacMax;
    }

    function setMontant($montant) {
        $this->montant = $montant;
    }

    function setTimbre($timbre) {
        $this->timbre = $timbre;
    }

    function setAnasser($anasser) {
        $this->anasser = $anasser;
    }

    function setGenre($genre) {
        $this->genre = $genre;
    }

    function setUsage($usage) {
        $this->usage = $usage;
    }

    function setCarrosserie($carrosserie) {
        $this->carrosserie = $carrosserie;
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
        return $this->montant;
    }

    public function estSupprimable(){
        return true;
    }
    
    public function __toString(){
        return $this->montant;
    }
    
    public function __construct()
    {
    }
    //fin behavior


}
