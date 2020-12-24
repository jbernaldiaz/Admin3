<?php

namespace IPNJ\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;   
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Envios
 *
 * @ORM\Table(name="envios")
 * @ORM\Entity(repositoryClass="IPNJ\AdminBundle\Repository\EnviosRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 *     fields={"iglesia", "mes", "anio"},
 *     errorPath="mes",
 *     message="Esta iglesia ya realizo el envio de este mes.")
 *
 */
class Envios
{
    /**
     * @ORM\ManyToOne(targetEntity="Iglesias", inversedBy="envios")
     * @ORM\JoinColumn(name="iglesia_id", referencedColumnName="id", onDelete="CASCADE")
     * 
     */ 
    protected $iglesia;

      /**
     * @ORM\ManyToOne(targetEntity="Zonas", inversedBy="envio")
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
     * @ORM\Column(name="d_diezmo", type="integer")
     * @Assert\NotBlank()
     */
    private $dDiezmo;

        /**
     * @var int
     *
     * @ORM\Column(name="f_solidario", type="integer")
     * @Assert\NotBlank()
     */
    private $fSolidario;

    /**
     * @var int
     *
     * @ORM\Column(name="cuota", type="integer")
     * @Assert\NotBlank()
     */
    private $cuota;

    /**
     * @var int
     *
     * @ORM\Column(name="d_personal", type="integer")
     * @Assert\NotBlank()
     */
    private $dPersonal;

    /**
     * @var int
     *
     * @ORM\Column(name="misionera", type="integer")
     * @Assert\NotBlank()
     */
    private $misionera;

    /**
     * @var int
     *
     * @ORM\Column(name="rayos", type="integer")
     * @Assert\NotBlank()
     */
    private $rayos;

    /**
     * @var int
     *
     * @ORM\Column(name="gavillas", type="integer")
     * @Assert\NotBlank()
     */
    private $gavillas;

    /**
     * @var int
     *
     * @ORM\Column(name="fmn", type="integer")
     * @Assert\NotBlank()
     */
    private $fmn;

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
     * @return Envios
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
     * @return Envios
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
     * @return Envios
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
     * @return Envios
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
     * @return Envios
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
     * @return Envios
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
     * @return Envios
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
     * @return Envios
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
     * Set dDiezmo
     *
     * @param integer $dDiezmo
     *
     * @return Envios
     */
    public function setDDiezmo($dDiezmo)
    {
        $this->dDiezmo = $dDiezmo;

        return $this;
    }

    /**
     * Get dDiezmo
     *
     * @return integer
     */
    public function getDDiezmo()
    {
        return $this->dDiezmo;
    }

    /**
     * Set fSolidario
     *
     * @param integer $fSolidario
     *
     * @return Envios
     */
    public function setFSolidario($fSolidario)
    {
        $this->fSolidario = $fSolidario;

        return $this;
    }

    /**
     * Get fSolidario
     *
     * @return integer
     */
    public function getFSolidario()
    {
        return $this->fSolidario;
    }

    /**
     * Set cuota
     *
     * @param integer $cuota
     *
     * @return Envios
     */
    public function setCuota($cuota)
    {
        $this->cuota = $cuota;

        return $this;
    }

    /**
     * Get cuota
     *
     * @return integer
     */
    public function getCuota()
    {
        return $this->cuota;
    }

    /**
     * Set dPersonal
     *
     * @param integer $dPersonal
     *
     * @return Envios
     */
    public function setDPersonal($dPersonal)
    {
        $this->dPersonal = $dPersonal;

        return $this;
    }

    /**
     * Get dPersonal
     *
     * @return integer
     */
    public function getDPersonal()
    {
        return $this->dPersonal;
    }

    /**
     * Set misionera
     *
     * @param integer $misionera
     *
     * @return Envios
     */
    public function setMisionera($misionera)
    {
        $this->misionera = $misionera;

        return $this;
    }

    /**
     * Get misionera
     *
     * @return integer
     */
    public function getMisionera()
    {
        return $this->misionera;
    }

    /**
     * Set rayos
     *
     * @param integer $rayos
     *
     * @return Envios
     */
    public function setRayos($rayos)
    {
        $this->rayos = $rayos;

        return $this;
    }

    /**
     * Get rayos
     *
     * @return integer
     */
    public function getRayos()
    {
        return $this->rayos;
    }

    /**
     * Set gavillas
     *
     * @param integer $gavillas
     *
     * @return Envios
     */
    public function setGavillas($gavillas)
    {
        $this->gavillas = $gavillas;

        return $this;
    }

    /**
     * Get gavillas
     *
     * @return integer
     */
    public function getGavillas()
    {
        return $this->gavillas;
    }

    /**
     * Set fmn
     *
     * @param integer $fmn
     *
     * @return Envios
     */
    public function setFmn($fmn)
    {
        $this->fmn = $fmn;

        return $this;
    }

    /**
     * Get fmn
     *
     * @return integer
     */
    public function getFmn()
    {
        return $this->fmn;
    }

    /**
     * Set total
     *
     * @param integer $total
     *
     * @return Envios
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
     * @return Envios
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
     * @return Envios
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
