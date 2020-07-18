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
    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';
    const ROLE_ADMIN = 'ROLE_SUPERVISEUR';
    const ROLE_USER = 'ROLE_USER';
    
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
     * @var string $telephone
     *
     * @ORM\Column(name="telephone", type="string", length=255, nullable=true)
     * 
     */
    private $telephone;
    
    /**
    * @ORM\ManyToOne(targetEntity="Group", inversedBy="users", cascade={"persist","refresh"})
    * @ORM\JoinColumn(name="groupe_id", referencedColumnName="id")
    * @Assert\NotBlank
    */
    protected $groupe;
    
    public function __construct()
    {
        parent::__construct();
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
        return $this->affectations == null || count($this->affectations) == 0;
    }
    
    public function __toString(){
        return $this->nom." ".$this->prenom;
    }
    function getTelephone() {
        return $this->telephone;
    }

    function setTelephone($telephone) {
        $this->telephone = $telephone;
    }
    
    function getGroupe() {
        return $this->groupe;
    }

    function setGroupe($groupe) {
        $this->groupe = $groupe;
    }
    
    /**
     * Returns the user roles
     *
     * @return array The roles
     */
    public function getRoles()
    {
        $roles = $this->roles;

        foreach ($this->getGroups() as $group) {
            $roles = array_merge($roles, $group->getRoles());
        }
        if($this->groupe!=null){
            $roles = array_merge($roles, $this->groupe->getRoles());
        }
        // we need to make sure to have at least one role
        $roles[] = static::ROLE_DEFAULT;

        return array_unique($roles);
    }
    
    public function hasRole($role)
    {
        return in_array(strtoupper($role), $this->getRoles(), true);
    }

    //Debut relation Utilisateur a plusieurs affectations
    /**
    * @ORM\OneToMany(targetEntity="Affectation", mappedBy="agent", cascade={"persist"})
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
     * Set affectations
     *
     * @param \Doctrine\Common\Collections\Collection $affectations
     */
    public function setAffectations(\Doctrine\Common\Collections\Collection $affectations)
    {
        $this->affectations = $affectations;
    }
    //Fin relation caisse a plusieurs affectations

    
    //Debut relation Utilisateur a plusieurs quittances
    /**
    * @ORM\OneToMany(targetEntity="Quittance", mappedBy="caissier", cascade={"persist"})
    */
    protected $quittances;
    
    /**
    * Add quittance
    *
    * @param AppBundle\Entity\Quittance $quittance
    */
    public function addQuittance(\AppBundle\Entity\Quittance $quittance)
    {
        $this->quittances[] = $quittance;
    }

    /**
     * Get quittances
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getQuittances()
    {
        return $this->quittances;
    }

    /**
     * Set quittances
     *
     * @param \Doctrine\Common\Collections\Collection $quittances
     */
    public function setQuittances(\Doctrine\Common\Collections\Collection $quittances)
    {
        $this->quittances = $quittances;
    }
    //Fin relation utilisateur a plusieurs quittances
}
