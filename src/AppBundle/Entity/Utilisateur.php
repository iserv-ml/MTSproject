<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Utilisateur
 *
 * @ORM\Table(name="utilisateur")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\UtilisateurRepository")
 * @UniqueEntity("username")
 * 
 */
class Utilisateur extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /** 
     * @ORM\Version 
     * @ORM\Column(type="integer") 
     */
    private $version;
    
    
     /**
     * @var string $nom
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $nom;
    
    /**
     * @var string $prenom
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $prenom;
    
    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Group")
     * @ORM\JoinTable(name="user_role",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     * @Assert\NotBlank
     */
    protected $groups;
    
    public function __construct()
    {
        parent::__construct();
        $this->nom = "Admin";
        $this->prenom = "Admin";
        // your own logic
    }
    
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

    function getLogin() {
        return $this->login;
    }

    function getNom() {
        return $this->nom;
    }

    function getPrenom() {
        return $this->prenom;
    }

    function setLogin($login) {
        $this->login = $login;
    }

    function setNom($nom) {
        $this->nom = $nom;
    }

    function setPrenom($prenom) {
        $this->prenom = $prenom;
    }
    
    
    
    public function getNomComplet(){
        return $this->nom." ".$this->prenom;
    }

    public function estSupprimable(){
        return true;
    }
    
    public function __toString(){
        return $this->nom." ".$this->prenom;
    }
    

}
