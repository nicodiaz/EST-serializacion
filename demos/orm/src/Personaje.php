<?php

/**
 * @Entity @Table(name="Personajes")
 **/
class Personaje
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
     * @ManyToOne(targetEntity="Tropa")
     */
    protected $tropa;

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

    public function setTropa($tropa)
    {
        $this->tropa = $tropa;
    }

    public function getTropa()
    {
        return $this->tropa;
    }
}