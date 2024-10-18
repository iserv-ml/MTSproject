<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Synchro
 *
 * @ORM\Table(name="synchro")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\SynchroRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Synchro
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
     * @var string $path
     *
     * @ORM\Column(name="path", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $path;
    
    /**
     * @var string $libelle
     *
     * @ORM\Column(name="libelle", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $libelle;
    
    /**
     * @var string $hash
     *
     * @ORM\Column(name="hash", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $hash;
    
    /**
     * @var string $checksum
     *
     * @ORM\Column(name="checksum", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $checksum;
    
    /**
     * @var string $type
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $type;
    
    /**
     * @var boolean $synchronise
     * @ORM\Column(name="synchronise", type="boolean", nullable=true)
     * 
     */
    private $synchronise;
    
    /**
     * @var string $response
     *
     * @ORM\Column(name="response", type="string", length=255, nullable=true)
     */
    private $response;

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
    
    function getPath() {
        return $this->path;
    }

    function getLibelle() {
        return $this->libelle;
    }

    function getType() {
        return $this->type;
    }

    function setPath($path) {
        $this->path = $path;
    }

    function setLibelle($libelle) {
        $this->libelle = $libelle;
    }

    function setType($type) {
        $this->type = $type;
    }
    
    function getHash() {
        return $this->hash;
    }

    function getChecksum() {
        return $this->checksum;
    }

    function setHash($hash) {
        $this->hash = $hash;
    }

    function setChecksum($checksum) {
        $this->checksum = $checksum;
    }
    
    function getSynchronise() {
        return $this->synchronise;
    }

    function setSynchronise($synchronise) {
        $this->synchronise = $synchronise;
    }  
    
    function getResponse() {
        return $this->response;
    }

    function setResponse($response) {
        $this->response = $response;
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
        return true;
    }
    
    public function __toString(){
        return $this->libelle;
    }
    
    public function __construct($path, $libelle, $hash, $checksum, $type, $synchronise)
    {
        $this->path = $path;
        $this->libelle = $libelle;
        $this->hash = $hash;
        $this->checksum = $checksum;
        $this->type = $type;
        $this->synchronise = $synchronise;
    }
    //fin behavior

}
