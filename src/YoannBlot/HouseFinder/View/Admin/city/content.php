<?php
/** @var \YoannBlot\HouseFinder\Model\Entity\City[] $cities */
?>
<h1>Administration des villes</h1>
<form action="" method="post">
    <h2><?= count($cities); ?> villes sélectionnées</h2>
    <ul class="selected-cities">
        <?php foreach ($cities as $city): ?>
            <li><?= $city->getName(); ?> / (<?= $city->getPostalCode(); ?>)</li>
        <?php endforeach; ?>
    </ul>

    <label for="add-city">Ajouter</label>
    <input type="search" name="add-city" id="add-city"/>
</form>