<?php 
/**
 * Lista los personajes creados
 */

require_once 'bootstrap.php';

$personajeRepo = $entityManager->getRepository('Personaje');
$personajes = $personajeRepo->findAll();

foreach ($personajes as $personaje) 
{
    echo sprintf("-%s\n", $personaje->getNombre());
}


