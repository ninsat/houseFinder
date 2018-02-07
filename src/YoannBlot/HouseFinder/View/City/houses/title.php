<?php
/** @var \YoannBlot\HouseFinder\Model\Entity\City $city */
/** @var \YoannBlot\HouseFinder\Model\Entity\House[] $houses */
?>
Recherche sur <?= $city->getName(); ?> <?= $city->getPostalCode(); ?> d'appartements ou maisons, <?= count($houses); ?> sont disponibles