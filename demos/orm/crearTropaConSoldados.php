<?php
require_once "bootstrap.php";

$tropa = new Tropa();
$tropa->setNombre("Compania 123");

$tropa->asignarSoldado($entityManager->find("Personaje", 1));
$tropa->asignarSoldado($entityManager->find("Personaje", 4));

$entityManager->persist($tropa);
$entityManager->flush();

echo "La tropa fue creada y asignada con el ID:" . $tropa->getId() . "\n";
