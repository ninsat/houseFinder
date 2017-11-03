<?php
/** @var \YoannBlot\HouseFinder\Model\Entity\City $city */
/** @var \YoannBlot\HouseFinder\Model\Entity\House[] $houses */
/** @var \YoannBlot\HouseFinder\Model\Entity\City[] $cities */
?>

<h1>Maisons / Appartements de <?= $city->getName(); ?></h1>
<nav>
    <ul class="badges">
        <?php foreach ($cities as $city): ?>
            <li><a href="/city/<?= $city->getId(); ?>/houses"><?= $city->getName(); ?></a></li>
        <?php endforeach; ?>
    </ul>
</nav>
<ul>
    <?php foreach ($houses as $house): ?>
        <li>
            <a href="/house/<?= $house->getId(); ?>">
                <?= $house->getRent(); ?>&euro;
                <?= $house->getTitle(); ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>