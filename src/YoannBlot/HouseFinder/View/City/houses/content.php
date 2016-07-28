<?php
/** @var \YoannBlot\HouseFinder\Model\Entity\City $city */
/** @var \YoannBlot\HouseFinder\Model\Entity\House[] $houses */
?>
<h1>Maisons / Appartements de <?= $city->getName(); ?></h1>

<ul>
    <?php foreach ($houses as $house): ?>
        <li>
            <?= $house->getRent(); ?>&euro;
            <?= $house->getTitle(); ?>
        </li>
    <?php endforeach; ?>
</ul>