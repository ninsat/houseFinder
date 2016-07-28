<?php
/** @var \YoannBlot\HouseFinder\Model\Entity\City $city */
/** @var \YoannBlot\HouseFinder\Model\Entity\House[] $houses */
?>
<?= $city->getName(); ?> <?= $city->getPostalCode(); ?> | Recherche d'appartements | <?= count($houses); ?> maisons disponibles à louer