<?php


namespace AppBundle\Entity;

use FOS\UserBundle\Entity\Group as BaseGroup;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\GroupRepository")
 * @ORM\Table(name="role")
 */
class Group extends BaseGroup
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
     * @var
     *
     * @ORM\OneToMany(targetEntity="Utilisateur", mappedBy="groupe", cascade={"persist"})
     */
    protected $users;
    
    
    public function __construct($name = "", $roles = array())
    {
        parent::__construct($name, $roles);
        $this->users = new ArrayCollection();
    }
    
    public function getId() {
        return $this->id;
    }

    public function getVersion() {
        return $this->version;
    }

    public function setVersion($version) {
        $this->version = $version;
    }

    public function __toString()
    {
        return (string) $this->getName();
    }
    
    /**
     * @param $user
     * @return $this
     */
    public function addUser($user)
    {
        $this->users[] = $user;
        $user->setGroup($this);

        return $this;
    }

    /**
     * @param $users
     */
    public function setUsers($users)
    {
        $this->users->clear();
        foreach ($users as $user) {
            $this->addUser($user);
        }
    }

    /**
     * @return mixed
     */
    public function getUsers()
    {
        return $this->users;
    }
    
    public function estSupprimable(){
        return $this->users == null || count($this->users) == 0;
    }
}