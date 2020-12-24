<?php

namespace IPNJ\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;   
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Asistencia
 *
 * @ORM\Table(name="asistencia")
 * @ORM\Entity(repositoryClass="IPNJ\AdminBundle\Repository\AsistenciaRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 *     fields={"iglesia", "mes", "anio"},
 *     errorPath="mes",
 *     message="Esta iglesia ya realizo el envio de este mes.")
 *
 */


class Asistencia
{
    /**
     * @ORM\ManyToOne(targetEntity="Iglesias", inversedBy="Asistencia")
     * @ORM\JoinColumn(name="iglesia_id", referencedColumnName="id", onDelete="CASCADE")
     * 
     */ 
    protected $iglesia;

      /**
     * @ORM\ManyToOne(targetEntity="Zonas", inversedBy="asistencia")
     * @ORM\JoinColumn(name="zona_id", referencedColumnName="id", onDelete="CASCADE")
     * @Assert\NotNull()
     */ 
    protected $zona;
    
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="iglesia_id", type="integer")
     */
    private $iglesiaId;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime")
     * * @Assert\NotNull()
     */
    private $fecha_at;
    
    /**
     *
     * @ORM\Column(name="mes", type="string", columnDefinition="ENUM('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre')")
     */
    private $mes;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="anio_at", type="datetime")
     */
    private $anio;
    
    /**
     * @var string
     *
     * @ORM\Column(name="operacion", type="string", length=150)
     * @Assert\NotBlank()
     */
    private $operacion;

    /**
     * @var string
     *
     * @ORM\Column(name="cajero", type="string", length=150)
     * @Assert\NotBlank()
     */
    private $cajero;


    /**
     * @var int
     *
     * @ORM\Column(name="aporte_a", type="integer")
     */
    private $aporteA;

    /**
     * @var int
     *
     * @ORM\Column(name="aporte_b", type="integer")
     */
    private $aporteB;

    /**
     * @var int
     *
     * @ORM\Column(name="total", type="integer")
     * @Assert\NotBlank()
     */
    private $total;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_at", type="datetime")
     */
    private $createAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="update_at", type="datetime")
     */
    private $updateAt;






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
     * Set iglesiaId
     *
     * @param integer $iglesiaId
     *
     * @return Asistencia
     */
    public function setIglesiaId($iglesiaId)
    {
        $this->iglesiaId = $iglesiaId;

        return $this;
    }

    /**
     * Get iglesiaId
     *
     * @return int
     */
    public function getIglesiaId()
    {
        return $this->iglesiaId;
    }



 
    /**
     * Set zona
     *
     * @param \IPNJ\AdminBundle\Entity\Zonas $zona
     *
     * @return Asistencia
     */
    public function setZona(\IPNJ\AdminBundle\Entity\Zonas $zona = null)
    {
        $this->zona = $zona;

        return $this;
    }

    /**
     * Get zona
     *
     * @return \IPNJ\AdminBundle\Entity\Iglesias
     */
    public function getZona()
    {
        return $this->zona;
    }

    /**
     * Set iglesia
     *
     * @param \IPNJ\AdminBundle\Entity\Iglesias $iglesia
     *
     * @return Asistencia
     */
    public function setIglesia(\IPNJ\AdminBundle\Entity\Iglesias $iglesia = null)
    {
        $this->iglesia = $iglesia;

        return $this;
    }

    /**
     * Get iglesia
     *
     * @return \IPNJ\AdminBundle\Entity\Iglesias
     */
    public function getIglesia()
    {
        return $this->iglesia;
    }

    /**
     * Set fechaAt
     *
     * @param \DateTime $fechaAt
     *
     * @return Asistencia
     */
    public function setFechaAt($fechaAt)
    {
        $this->fecha_at = $fechaAt;

        return $this;
    }

    /**
     * Get fechaAt
     *
     * @return \DateTime
     */
    public function getFechaAt()
    {
        return $this->fecha_at;
    }

    /**
     * Set mes
     *
     * @param string $mes
     *
     * @return Asistencia
     */
    public function setMes($mes)
    {
        $this->mes = $mes;

        return $this;
    }

    /**
     * Get mes
     *
     * @return string
     */
    public function getMes()
    {
        return $this->mes;
    }

    /**
     * @ORM\PrePersist
     */
    public function setAnioAtValue()
    {
        $this->anioAt = new \DateTime('Y');
    }


    /**
     * Set anio
     *
     * @param \DateTime $anio
     *
     * @return Asistencia
     */
    public function setAnio($anio)
    {
        $this->anio = $anio;

        return $this;
    }

    /**
     * Get anio
     *
     * @return \DateTime
     */
    public function getAnio()
    {
        return $this->anio;
    }

    /**
     * Set operacion
     *
     * @param string $operacion
     *
     * @return Asistencia
     */
    public function setOperacion($operacion)
    {
        $this->operacion = $operacion;

        return $this;
    }

    /**
     * Get operacion
     *
     * @return string
     */
    public function getOperacion()
    {
        return $this->operacion;
    }

    /**
     * Set cajero
     *
     * @param string $cajero
     *
     * @return Asistencia
     */
    public function setCajero($cajero)
    {
        $this->cajero = $cajero;

        return $this;
    }

    /**
     * Get cajero
     *
     * @return string
     */
    public function getCajero()
    {
        return $this->cajero;
    }




    /**
     * Set aporteA
     *
     * @param integer $aporteA
     *
     * @return Asistencia
     */
    public function setAporteA($aporteA)
    {
        $this->aporteA = $aporteA;

        return $this;
    }

    /**
     * Get aporteA
     *
     * @return int
     */
    public function getAporteA()
    {
        return $this->aporteA;
    }

    /**
     * Set aporteB
     *
     * @param integer $aporteB
     *
     * @return Asistencia
     */
    public function setAporteB($aporteB)
    {
        $this->aporteB = $aporteB;

        return $this;
    }

    /**
     * Get aporteB
     *
     * @return int
     */
    public function getAporteB()
    {
        return $this->aporteB;
    }


 /**
     * Set total
     *
     * @param integer $total
     *
     * @return Asistencia
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return integer
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set createAt
     *
     * @param \DateTime $createAt
     *
     * @return Asistencia
     */
    public function setCreateAt($createAt)
    {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * Get createAt
     *
     * @return \DateTime
     */
    public function getCreateAt()
    {
        return $this->createAt;
    }

    /**
     * Set updateAt
     *
     * @param \DateTime $updateAt
     *
     * @return Asistencia
     */
    public function setUpdateAt($updateAt)
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    /**
     * Get updateAt
     *
     * @return \DateTime
     */
    public function getUpdateAt()
    {
        return $this->updateAt;
    }
    
    /**
     * @ORM\PrePersist
     */
    public function setCreateAtValue()
    {
        $this->createAt = new \DateTime();
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setUpdateAtValue()
    {
        $this->updateAt = new \DateTime();
    }



}

