<?php

namespace IPNJ\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
/**
 * Iglesias
 *
 * @ORM\Table(name="iglesias")
 * @ORM\Entity(repositoryClass="IPNJ\AdminBundle\Repository\IglesiasRepository")
 * @UniqueEntity("iglesia")
 */
class Iglesias implements AdvancedUserInterface, \Serializable 
{

    /**
     * @ORM\ManyToOne(targetEntity="Zonas", inversedBy="iglesias")
     * @ORM\JoinColumn(name="zona_id", referencedColumnName="id", onDelete="CASCADE")
     */ 
    protected $zona;

     /**
     * @ORM\OneToMany(targetEntity="Envios", mappedBy="iglesia")
     */ 
    protected $envios;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=50)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $username;


    /**
     * @var string
     *
     * @ORM\Column(name="iglesia", type="string", length=100)
     * @Assert\NotBlank()
     */
    private $iglesia;

    /**
     * @var int
     *
     * @ORM\Column(name="zona_id", type="integer")
     * @Assert\Choice(choices = {"1", "2", "3"})
     * 
     */
    private $zonaId;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     * 
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", columnDefinition="ENUM('ROLE_ADMIN', 'ROLE_SUPER', 'ROLE_USER')", length=50)
     * @Assert\NotBlank()
     * @Assert\Choice(choices = {"ROLE_ADMIN", "ROLE_SUPER", "ROLE_USER"})
     */
    private $role;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;
    
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set iglesia
     *
     * @param string $iglesia
     *
     * @return Iglesias
     */
    public function setIglesia($iglesia)
    {
        $this->iglesia = $iglesia;

        return $this;
    }

    /**
     * Get iglesia
     *
     * @return string
     */
    public function getIglesia()
    {
        return $this->iglesia;
    }

    /**
     * Set zonaId
     *
     * @param integer $zonaId
     *
     * @return Iglesias
     */
    public function setZonaId($zonaId)
    {
        $this->zonaId = $zonaId;

        return $this;
    }

    /**
     * Get zonaId
     *
     * @return int
     */
    public function getZonaId()
    {
        return $this->zonaId;
    }

 /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

 /**
     * Set role
     *
     * @param string $role
     * @return User
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string 
     */
    public function getRole()
    {
        return $this->role;
    }



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->iglesias = new ArrayCollection();
        $this->isActive = true;
    }



    /**
     * Add iglesia
     *
     * @param \IPNJ\AdminBundle\Entity\Iglesias $iglesia
     *
     * @return Iglesias
     */
    public function addIglesia(\IPNJ\AdminBundle\Entity\Iglesias $iglesia)
    {
        $this->iglesias[] = $iglesia;

        return $this;
    }

    /**
     * Remove iglesia
     *
     * @param \IPNJ\AdminBundle\Entity\Iglesias $iglesia
     */
    public function removeIglesia(\IPNJ\AdminBundle\Entity\Iglesias $iglesia)
    {
        $this->iglesias->removeElement($iglesia);
    }

    /**
     * Get iglesias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIglesias()
    {
        return $this->iglesias;
    }

    /**
     * Set zona
     *
     * @param \IPNJ\AdminBundle\Entity\Zonas $zona
     *
     * @return Iglesias
     */
    public function setZona(\IPNJ\AdminBundle\Entity\Zonas $zona)
    {
        $this->zona = $zona;

        return $this;
    }

    /**
     * Get zona
     *
     * @return \IPNJ\AdminBundle\Entity\Zonas
     */
    public function getZona()
    {
        return $this->zona;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }


    /**
     * Add envio
     *
     * @param \IPNJ\AdminBundle\Entity\Envios $envio
     *
     * @return Iglesias
     */
    public function addEnvio(\IPNJ\AdminBundle\Entity\Envios $envio)
    {
        $this->envios[] = $envio;

        return $this;
    }

    /**
     * Remove envio
     *
     * @param \IPNJ\AdminBundle\Entity\Envios $envio
     */
    public function removeEnvio(\IPNJ\AdminBundle\Entity\Envios $envio)
    {
        $this->envios->removeElement($envio);
    }

    /**
     * Get envios
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEnvios()
    {
        return $this->envios;
    }
    /**
 * 
 *
 * @return string String representation of this class
 */
public function __toString()
{
    return $this->iglesia;
}

    public function getRoles()
    {
        return array($this->role);
    }
    
    public function getSalt()
    {
        return null;
    }
    
    public function eraseCredentials()
    {
        
    }

        /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->isActive
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->isActive
        ) = unserialize($serialized);
    }   
    
    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
       return $this->isActive;
    }
}
