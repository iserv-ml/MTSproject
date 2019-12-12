<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * CodeMahaResultat
 *
 * @ORM\Table(name="codemaharesultat")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\CodeMahaResultatRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class CodeMahaResultat
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
     * @var string $libelle
     *
     * @ORM\Column(name="libelle", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $libelle;
    
    /**
     * @var string $type
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $type;
    
    /**
     * @var string $valeur
     *
     * @ORM\Column(name="valeur", type="string", length=255, nullable=true)
     */
    private $valeur;
    
    /**
     * @var integer $minimum
     *
     * @ORM\Column(name="minimum", type="float", nullable=true)
     */
    private $minimum;
    
    /**
     * @var integer $maximum
     *
     * @ORM\Column(name="maximum", type="float", nullable=true)
     */
    private $maximum;
    
    /**
     * @var string $detail
     *
     * @ORM\Column(name="detail", type="string", length=255, nullable=true)
     * 
     */
    private $detail;
    
    /**
     * @var boolean $actif
     *
     * @ORM\Column(name="actif", type="boolean", nullable=false)
     * 
     */
    private $actif;
    
    /**
     * @var boolean $reussite
     *
     * @ORM\Column(name="reussite", type="boolean", nullable=false)
     * 
     */
    private $reussite;
    
    /**
    * @ORM\ManyToOne(targetEntity="Controle", inversedBy="codeMahaResultats", cascade={"persist","refresh"})
    * @ORM\JoinColumn(name="controle_id", referencedColumnName="id")
    * @Assert\NotBlank
    */
    protected $controle;
    
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

    function getLibelle() {
        return $this->libelle;
    }

    function setLibelle($libelle) {
        $this->libelle = $libelle;
    }

    function getDetail() {
        return $this->detail;
    }

    function getActif() {
        return $this->actif;
    }

    function getCategorie() {
        return $this->categorie;
    }

    function setDetail($detail) {
        $this->detail = $detail;
    }

    function setActif($actif) {
        $this->actif = $actif;
    }

    function setCategorie($categorie) {
        $this->categorie = $categorie;
    }
    
    function getControle() {
        return $this->controle;
    }

    function setControle($controle) {
        $this->controle = $controle;
    }
    function getReussite() {
        return $this->reussite;
    }

    function setReussite($reussite) {
        $this->reussite = $reussite;
    }
    
    function getType() {
        return $this->type;
    }

    function getValeur() {
        return $this->valeur;
    }

    function getMinimum() {
        return $this->minimum;
    }

    function getMaximum() {
        return $this->maximum;
    }

    function setType($type) {
        $this->type = $type;
    }

    function setValeur($valeur) {
        $this->valeur = $valeur;
    }

    function setMinimum($minimum) {
        $this->minimum = $minimum;
    }

    function setMaximum($maximum) {
        $this->maximum = $maximum;
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
        return $this->vehicules == null || count($this->vehicules)==0;
    }
    
    public function __toString(){
        return $this->libelle;
    }
    
    public function __construct()
    {
        $this->users = new ArrayCollection();
    }
    
    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload = null)
    {
        if($this->getMinimum() != null && $this->getMinimum()  >= $this->getMaximum()){
             $context->buildViolation('Le max doit être supérieur au min')
                ->atPath('max')
                ->addViolation();
        }
    }

}
