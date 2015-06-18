<?php
// create_product.php
require_once "bootstrap.php";

$tropa = new Tropa();
$tropa->setNombre("Compania 123");

$entityManager->persist($tropa);

$soldado = $entityManager->find("Personaje", 2);
$soldado->setTropa($tropa);
$entityManager->persist($soldado);

$soldado = $entityManager->find("Personaje", 3);
$soldado->setTropa($soldado);

$entityManager->flush();

echo "La tropa fue creada y asignada con el ID:" . $tropa->getId() . "\n";