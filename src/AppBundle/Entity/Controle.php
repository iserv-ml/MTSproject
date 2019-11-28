<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Controle
 *
 * @ORM\Table(name="controle")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ControleRepository")
 * @UniqueEntity("code")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Controle
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
     * @var string $code
     *
     * @ORM\Column(name="code", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $code;
    
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
     * @var string $type
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $type;
    
    /**
     * @var string $unite
     *
     * @ORM\Column(name="unite", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $unite;
    
    /**
     * @var integer $minimum
     *
     * @ORM\Column(name="minimum", type="integer", nullable=false)
     * @Assert\NotBlank
     */
    private $minimum;
    
    /**
     * @var integer $maximum
     *
     * @ORM\Column(name="maximum", type="integer", nullable=false)
     * @Assert\NotBlank
     */
    private $maximum;
    
    /**
    * @ORM\ManyToOne(targetEntity="CategorieControle", inversedBy="controles", cascade={"persist","refresh"})
    * @ORM\JoinColumn(name="categorie_id", referencedColumnName="id")
    * @Assert\NotBlank
    */
    protected $categorie;
    
    //Debut relation Controle a plusieurs CodeMahaResultat
    /**
    * @ORM\OneToMany(targetEntity="CodeMahaResultat", mappedBy="controle", cascade={"persist"})
    */
    protected $codeMahaResultats;
    
    /**
    * Add codeMahaResultat
    *
    * @param AppBundle\Entity\CodeMahaResultat $codeMahaResultat
    */
    public function addCodeMahaResultat(\AppBundle\Entity\CodeMahaResultat $codeMahaResultat)
    {
        $this->codeMahaResultats[] = $codeMahaResultat;
    }

    /**
     * Get codeMahaResultat
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCodeMahaResultats()
    {
        return $this->codeMahaResultats;
    }

    /**
     * Set codeMahaResultat
     *
     * @param \Doctrine\Common\Collections\Collection $codeMahaResultats
     */
    public function setCodeMahaResultats(\Doctrine\Common\Collections\Collection $codeMahaResultats)
    {
        $this->codeMahaResultats = $codeMahaResultats;
    }
    //Fin relation Controle a plusieurs CodeMahaResultat
    
    //Debut relation Controle a plusieurs résultat
    /**
    * @ORM\OneToMany(targetEntity="Resultat", mappedBy="controle", cascade={"persist"})
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

    function getLibelle() {
        return $this->libelle;
    }

    function setLibelle($libelle) {
        $this->libelle = $libelle;
    }
    
    function getCode() {
        return $this->code;
    }

    function setCode($code) {
        $this->code = $code;
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
    function getType() {
        return $this->type;
    }

    function setType($type) {
        $this->type = $type;
    }
    
    function getUnite() {
        return $this->unite;
    }

    function getMinimum() {
        return $this->minimum;
    }

    function getMaximum() {
        return $this->maximum;
    }

    function setUnite($unite) {
        $this->unite = $unite;
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
        $this->minimum = 0;
        $this->maximum = 0;
    }

}
