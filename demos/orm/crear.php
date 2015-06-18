<?php
// create_product.php
require_once "bootstrap.php";

$personaje = new Personaje();

$personaje->setNombre('Cyborg RE01');

$entityManager->persist($personaje);
$entityManager->flush();

echo "El personaje fue creado con el ID:" . $personaje->getId() . "\n";