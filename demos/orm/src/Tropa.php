<?php
/**
 * @Entity @Table(name="Tropas")
 **/
use Doctrine\Common\Collections\ArrayCollection;
class Tropa {
	
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
	private $soldados = null;
	public function __construct() {
		$this->soldados = new ArrayCollection ();
	}
	public function asignarSoldado($personaje) {
		$personaje->setTropa ($this);
		$this->soldados [] = $personaje;
	}
	
	/**
	 *
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 *
	 * @return string
	 */
	public function getNombre() {
		return $this->nombre;
	}
	
	/**
	 *
	 * @param string $nombre        	
	 */
	public function setNombre($nombre) {
		$this->nombre = $nombre;
	}
	
	/**
	 *
	 * @return the $soldados
	 */
	public function getSoldados() {
		return $this->soldados;
	}
	
	/**
	 *
	 * @param
	 *        	Ambigous <\Doctrine\Common\Collections\ArrayCollection, unknown> $soldados
	 */
	public function setSoldados($soldados) {
		$this->soldados = $soldados;
	}
}