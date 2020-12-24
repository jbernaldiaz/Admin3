<?php

namespace IPNJ\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Zonas
 *
 * @ORM\Table(name="zonas")
 * @ORM\Entity(repositoryClass="IPNJ\AdminBundle\Repository\ZonasRepository")
 */
class Zonas
{


 /**
     * @ORM\OneToMany(targetEntity="Iglesias", mappedBy="zona")
     */ 
    protected $iglesias;
    
     /**
     * @ORM\OneToMany(targetEntity="Envios", mappedBy="zona")
     */ 
    protected $envio;

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
     * @ORM\Column(name="zona", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $zona;
 


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
     * Set zona
     *
     * @param string $zona
     *
     * @return Zonas
     */
    public function setZona($zona)
    {
        $this->zona = $zona;

        return $this;
    }

    /**
     * Get zona
     *
     * @return string
     */
    public function getZona()
    {
        return $this->zona;
    }

    /**
     * Set zonas
     *
     * @param \IPNJ\AdminBundle\Entity\Zonas $zonas
     *
     * @return Zonas
     */
    public function setZonas(\IPNJ\AdminBundle\Entity\Zonas $zonas = null)
    {
        $this->zonas = $zonas;

        return $this;
    }

    /**
     * Get zonas
     *
     * @return \IPNJ\AdminBundle\Entity\Zonas
     */
    public function getZonas()
    {
        return $this->zonas;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->iglesias = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add iglesia
     *
     * @param \IPNJ\AdminBundle\Entity\Iglesias $iglesia
     *
     * @return Zonas
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
 * 
 *
 * @return string String representation of this class
 */
public function __toString()
{
    return $this->zona;
}

    /**
     * Add envio
     *
     * @param \IPNJ\AdminBundle\Entity\Envios $envio
     *
     * @return Zonas
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
     * Get envio
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEnvio()
    {
        return $this->envio;
    }
}
