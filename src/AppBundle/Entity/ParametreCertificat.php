<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * ParametreCertificat
 *
 * @ORM\Table(name="parametre_certificat")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ParametreCertificatRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @Gedmo\Loggable
 */
class ParametreCertificat
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
     * @var integer $largeurVolet1
     *
     * @ORM\Column(name="largeur_volet1", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $largeurVolet1;
    
    /**
     * @var integer $hauteurVolet1
     *
     * @ORM\Column(name="hauteur_volet1", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $hauteurVolet1;
    
    /**
     * @var integer $vcertificat1
     *
     * @ORM\Column(name="vcertificat1", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $vcertificat1;
    
    /**
     * @var integer $hcertificat1
     *
     * @ORM\Column(name="hcertificat1", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $hcertificat1;
    
    /**
     * @var integer $vnom1
     *
     * @ORM\Column(name="vnom1", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $vnom1;
    
    /**
     * @var integer $hnom1
     *
     * @ORM\Column(name="hnom1", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $hnom1;
    
    /**
     * @var integer $wnom1
     *
     * @ORM\Column(name="wnom1", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $wnom1;
    
    /**
     * @var integer $vadresse1
     *
     * @ORM\Column(name="vadresse1", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $vadresse1;
    
    /**
     * @var integer $hadresse1
     *
     * @ORM\Column(name="hadresse1", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $hadresse1;
    
    /**
     * @var integer $wadresse1
     *
     * @ORM\Column(name="wadresse1", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $wadresse1;
    
    /**
     * @var integer $vimmattriculation1
     *
     * @ORM\Column(name="vimmattriculation1", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $vimmattriculation1;
    
    /**
     * @var integer $himmattriculation1
     *
     * @ORM\Column(name="himmattriculation1", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $himmattriculation1;
    
    /**
     * @var integer $wimmattriculation1
     *
     * @ORM\Column(name="wimmattriculation1", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $wimmattriculation1;
    
    /**
     * @var integer $vserie1
     *
     * @ORM\Column(name="vserie1", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $vserie1;
    
    /**
     * @var integer $hserie1
     *
     * @ORM\Column(name="hserie1", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $hserie1;
    
    /**
     * @var integer $wserie1
     *
     * @ORM\Column(name="wserie1", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $wserie1;
    
    /**
     * @var integer $largeurVolet2
     *
     * @ORM\Column(name="largeur_volet2", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $largeurVolet2;
    
    /**
     * @var integer $hauteurVolet2
     *
     * @ORM\Column(name="hauteur_volet2", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $hauteurVolet2;
    
    /**
     * @var integer $vcertificat2
     *
     * @ORM\Column(name="vcertificat2", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $vcertificat2;
    
    /**
     * @var integer $hcertificat2
     *
     * @ORM\Column(name="hcertificat2", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $hcertificat2;
    
    /**
     * @var integer $vnom2
     *
     * @ORM\Column(name="vnom2", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $vnom2;
    
    /**
     * @var integer $hnom2
     *
     * @ORM\Column(name="hnom2", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $hnom2;
    
    /**
     * @var integer $wnom2
     *
     * @ORM\Column(name="wnom2", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $wnom2;
    
    /**
     * @var integer $vadresse2
     *
     * @ORM\Column(name="vadresse2", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $vadresse2;
    
    /**
     * @var integer $hadresse2
     *
     * @ORM\Column(name="hadresse2", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $hadresse2;
    
    /**
     * @var integer $wadresse2
     *
     * @ORM\Column(name="wadresse2", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $wadresse2;
    
    /**
     * @var integer $vimmattriculation2
     *
     * @ORM\Column(name="vimmattriculation2", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $vimmattriculation2;
    
    /**
     * @var integer $himmattriculation2
     *
     * @ORM\Column(name="himmattriculation2", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $himmattriculation2;
    
    /**
     * @var integer $wimmattriculation2
     *
     * @ORM\Column(name="wimmattriculation2", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $wimmattriculation2;
    
    /**
     * @var integer $vserie2
     *
     * @ORM\Column(name="vserie2", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $vserie2;
    
    /**
     * @var integer $hserie2
     *
     * @ORM\Column(name="hserie2", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $hserie2;
    
    /**
     * @var integer $wserie2
     *
     * @ORM\Column(name="wserie2", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $wserie2;
    
    /**
     * @var integer $largeurVolet3
     *
     * @ORM\Column(name="largeur_volet3", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $largeurVolet3;
    
    /**
     * @var integer $hauteurVolet3
     *
     * @ORM\Column(name="hauteur_volet3", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $hauteurVolet3;
    
    /**
     * @var integer $vcertificat3
     *
     * @ORM\Column(name="vcertificat3", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $vcertificat3;
    
    /**
     * @var integer $hcertificat3
     *
     * @ORM\Column(name="hcertificat3", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $hcertificat3;
    
    /**
     * @var integer $vimmattriculation3
     *
     * @ORM\Column(name="vimmattriculation3", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $vimmattriculation3;
    
    /**
     * @var integer $himmattriculation3
     *
     * @ORM\Column(name="himmattriculation3", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $himmattriculation3;
    
    /**
     * @var integer $vvalidite3
     *
     * @ORM\Column(name="vvalidite3", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $vvalidite3;
    
    /**
     * @var integer $hvalidite3
     *
     * @ORM\Column(name="hvalidite3", type="float", nullable=false)
     * @Assert\NotBlank
     */
    private $hvalidite3;
    
    /**
     * @var string $agentModification
     *
     * 
     * @ORM\ManyToOne(targetEntity="Utilisateur", inversedBy="vehicules", cascade={"persist","refresh"})
     * @ORM\JoinColumn(name="utilisateur_modif_id", referencedColumnName="id")
     */
    private $agentModification;
    
    

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
    
    public function getLargeurVolet1() {
        return $this->largeurVolet1;
    }

    public function getHauteurVolet1() {
        return $this->hauteurVolet1;
    }

    public function getVcertificat1() {
        return $this->vcertificat1;
    }

    public function getHcertificat1() {
        return $this->hcertificat1;
    }

    public function getVnom1() {
        return $this->vnom1;
    }

    public function getHnom1() {
        return $this->hnom1;
    }

    public function getWnom1() {
        return $this->wnom1;
    }

    public function getVadresse1() {
        return $this->vadresse1;
    }

    public function getHadresse1() {
        return $this->hadresse1;
    }

    public function getWadresse1() {
        return $this->wadresse1;
    }

    public function getVimmattriculation1() {
        return $this->vimmattriculation1;
    }

    public function getHimmattriculation1() {
        return $this->himmattriculation1;
    }

    public function getWimmattriculation1() {
        return $this->wimmattriculation1;
    }

    public function getVserie1() {
        return $this->vserie1;
    }

    public function getHserie1() {
        return $this->hserie1;
    }

    public function getWserie1() {
        return $this->wserie1;
    }

    public function getLargeurVolet2() {
        return $this->largeurVolet2;
    }

    public function getHauteurVolet2() {
        return $this->hauteurVolet2;
    }

    public function getVcertificat2() {
        return $this->vcertificat2;
    }

    public function getHcertificat2() {
        return $this->hcertificat2;
    }

    public function getVnom2() {
        return $this->vnom2;
    }

    public function getHnom2() {
        return $this->hnom2;
    }

    public function getWnom2() {
        return $this->wnom2;
    }

    public function getVadresse2() {
        return $this->vadresse2;
    }

    public function getHadresse2() {
        return $this->hadresse2;
    }

    public function getWadresse2() {
        return $this->wadresse2;
    }

    public function getVimmattriculation2() {
        return $this->vimmattriculation2;
    }

    public function getHimmattriculation2() {
        return $this->himmattriculation2;
    }

    public function getWimmattriculation2() {
        return $this->wimmattriculation2;
    }

    public function getVserie2() {
        return $this->vserie2;
    }

    public function getHserie2() {
        return $this->hserie2;
    }

    public function getWserie2() {
        return $this->wserie2;
    }

    public function getLargeurVolet3() {
        return $this->largeurVolet3;
    }

    public function getHauteurVolet3() {
        return $this->hauteurVolet3;
    }

    public function getVcertificat3() {
        return $this->vcertificat3;
    }

    public function getHcertificat3() {
        return $this->hcertificat3;
    }

    public function getVimmattriculation3() {
        return $this->vimmattriculation3;
    }

    public function getHimmattriculation3() {
        return $this->himmattriculation3;
    }

    public function getVvalidite3() {
        return $this->vvalidite3;
    }

    public function getHvalidite3() {
        return $this->hvalidite3;
    }

    public function setLargeurVolet1($largeurVolet1) {
        $this->largeurVolet1 = $largeurVolet1;
    }

    public function setHauteurVolet1($hauteurVolet1) {
        $this->hauteurVolet1 = $hauteurVolet1;
    }

    public function setVcertificat1($vcertificat1) {
        $this->vcertificat1 = $vcertificat1;
    }

    public function setHcertificat1($hcertificat1) {
        $this->hcertificat1 = $hcertificat1;
    }

    public function setVnom1($vnom1) {
        $this->vnom1 = $vnom1;
    }

    public function setHnom1($hnom1) {
        $this->hnom1 = $hnom1;
    }

    public function setWnom1($wnom1) {
        $this->wnom1 = $wnom1;
    }

    public function setVadresse1($vadresse1) {
        $this->vadresse1 = $vadresse1;
    }

    public function setHadresse1($hadresse1) {
        $this->hadresse1 = $hadresse1;
    }

    public function setWadresse1($wadresse1) {
        $this->wadresse1 = $wadresse1;
    }

    public function setVimmattriculation1($vimmattriculation1) {
        $this->vimmattriculation1 = $vimmattriculation1;
    }

    public function setHimmattriculation1($himmattriculation1) {
        $this->himmattriculation1 = $himmattriculation1;
    }

    public function setWimmattriculation1($wimmattriculation1) {
        $this->wimmattriculation1 = $wimmattriculation1;
    }

    public function setVserie1($vserie1) {
        $this->vserie1 = $vserie1;
    }

    public function setHserie1($hserie1) {
        $this->hserie1 = $hserie1;
    }

    public function setWserie1($wserie1) {
        $this->wserie1 = $wserie1;
    }

    public function setLargeurVolet2($largeurVolet2) {
        $this->largeurVolet2 = $largeurVolet2;
    }

    public function setHauteurVolet2($hauteurVolet2) {
        $this->hauteurVolet2 = $hauteurVolet2;
    }

    public function setVcertificat2($vcertificat2) {
        $this->vcertificat2 = $vcertificat2;
    }

    public function setHcertificat2($hcertificat2) {
        $this->hcertificat2 = $hcertificat2;
    }

    public function setVnom2($vnom2) {
        $this->vnom2 = $vnom2;
    }

    public function setHnom2($hnom2) {
        $this->hnom2 = $hnom2;
    }

    public function setWnom2($wnom2) {
        $this->wnom2 = $wnom2;
    }

    public function setVadresse2($vadresse2) {
        $this->vadresse2 = $vadresse2;
    }

    public function setHadresse2($hadresse2) {
        $this->hadresse2 = $hadresse2;
    }

    public function setWadresse2($wadresse2) {
        $this->wadresse2 = $wadresse2;
    }

    public function setVimmattriculation2($vimmattriculation2) {
        $this->vimmattriculation2 = $vimmattriculation2;
    }

    public function setHimmattriculation2($himmattriculation2) {
        $this->himmattriculation2 = $himmattriculation2;
    }

    public function setWimmattriculation2($wimmattriculation2) {
        $this->wimmattriculation2 = $wimmattriculation2;
    }

    public function setVserie2($vserie2) {
        $this->vserie2 = $vserie2;
    }

    public function setHserie2($hserie2) {
        $this->hserie2 = $hserie2;
    }

    public function setWserie2($wserie2) {
        $this->wserie2 = $wserie2;
    }

    public function setLargeurVolet3($largeurVolet3) {
        $this->largeurVolet3 = $largeurVolet3;
    }

    public function setHauteurVolet3($hauteurVolet3) {
        $this->hauteurVolet3 = $hauteurVolet3;
    }

    public function setVcertificat3($vcertificat3) {
        $this->vcertificat3 = $vcertificat3;
    }

    public function setHcertificat3($hcertificat3) {
        $this->hcertificat3 = $hcertificat3;
    }

    public function setVimmattriculation3($vimmattriculation3) {
        $this->vimmattriculation3 = $vimmattriculation3;
    }

    public function setHimmattriculation3($himmattriculation3) {
        $this->himmattriculation3 = $himmattriculation3;
    }

    public function setVvalidite3($vvalidite3) {
        $this->vvalidite3 = $vvalidite3;
    }

    public function setHvalidite3($hvalidite3) {
        $this->hvalidite3 = $hvalidite3;
    }
            
    //BEHAVIOR
    /**
     * @var string $creePar
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\Column(type="string")
     */
    private $creePar;
    
    //BEHAVIOR
    /**
     * @var string $agentCreation
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="Utilisateur", inversedBy="vehicules", cascade={"persist","refresh"})
     * @ORM\JoinColumn(name="utilisateur_id", referencedColumnName="id")
     */
    private $agentCreation;

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

    public function estSupprimable(){
        return false;
    }
    
    public function __construct()
    {
    }
    
    public function getAgentModification() {
        return $this->agentModification;
    }

    public function getAgentCreation() {
        return $this->agentCreation;
    }

    public function setAgentModification($agentModification) {
        $this->agentModification = $agentModification;
    }

    public function setAgentCreation($agentCreation) {
        $this->agentCreation = $agentCreation;
    }


}
