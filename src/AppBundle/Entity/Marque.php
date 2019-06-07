<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Marque
 *
 * @ORM\Table(name="marque")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\MarqueRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @UniqueEntity("code")
 */
class Marque
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
   
   //Debut relation Marque a plusieurs modèle
    /**
    * @ORM\OneToMany(targetEntity="Modele", mappedBy="marque", cascade={"persist"})
    */
    protected $modeles;
    
    /**
    * Add modele
    *
    * @param AppBundle\Entity\Modele $modele
    */
    public function addModele(\AppBundle\Entity\Modele $modele)
    {
        $this->modeles[] = $modele;
    }

    /**
     * Get modeles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getModeles()
    {
        return $this->modeles;
    }

    /**
     * Set modeles
     *
     * @param \Doctrine\Common\Collections\Collection $modeles
     */
    public function setModeles(\Doctrine\Common\Collections\Collection $modeles)
    {
        $this->modeles = $modeles;
    }
    //Fin relation marque a plusieurs modeles

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

    function getCode() {
        return $this->code;
    }

    function setLibelle($libelle) {
        $this->libelle = $libelle;
    }

    function setCode($code) {
        $this->code = $code;
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
        return $this->modeles == null || count($this->modeles)==0;
    }
    
    public function __toString(){
        return $this->libelle;
    }
    
    public function __construct()
    {
        $this->modeles = new ArrayCollection();
    }
    //fin behavior


}
