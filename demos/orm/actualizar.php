<?php
// update_product.php <id> <new-name>
require_once "bootstrap.php";

$id = 2;
$personaje = $entityManager->find('Personaje', $id);

if (empty($personaje))
{
    echo "El personaje con $id no existe.\n";
    exit(1);
}

// Encontro la entity en el almacenamiento
$personaje->setNombre('Nuevo Nombre Cybor');
$entityManager->flush();