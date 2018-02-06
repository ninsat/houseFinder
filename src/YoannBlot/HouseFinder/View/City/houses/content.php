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
<h2><?= count($houses); ?> maisons à louer</h2>
<ul>
    <?php foreach ($houses as $house): ?>
        <li>
            <a href="<?= $house->getUrl(); ?>">
                <?= $house->getRent(); ?>&euro;
                <?= $house->getTitle(); ?>
                <?= $house->getCity()->getName(); ?>
                <time><?= $house->getDate()->format('d/m/Y'); ?></time>
            </a>
        </li>
    <?php endforeach; ?>
</ul>