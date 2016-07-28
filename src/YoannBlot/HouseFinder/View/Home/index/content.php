<?php
/** @var \YoannBlot\HouseFinder\Model\Entity\City[] $cities */
?>
<h1>Accueil</h1>
<nav>
    <ul class="badges">
        <?php foreach ($cities as $city): ?>
            <li><a href="/city/<?= $city->getId(); ?>/houses"><?= $city->getName(); ?></a></li>
        <?php endforeach; ?>
    </ul>
</nav>