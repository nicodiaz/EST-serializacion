<?php
/**
 * @Entity @Table(name="Tropas")
 **/
use Doctrine\Common\Collections\ArrayCollection;

class Tropa
{

    /**
     * @Id @Column(type="integer") @GeneratedValue *
     */
    private $id;

    /**
     * @Column(type="string") *
     */
    private $nombre;

    /**
     * @OneToMany(targetEntity="Personaje", mappedBy="tropa")
     */
    private $soldados;

    public function __construct()
    {
        $this->soldados = new ArrayCollection();
    }

    public function asignarSoldado($personaje)
    {
        $this->soldados[] = $personaje;
    }

    /**
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     *
     * @param string $nombre            
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
}