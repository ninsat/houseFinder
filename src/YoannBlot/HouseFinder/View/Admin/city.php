<?php
/** @var \YoannBlot\HouseFinder\Model\Entity\City[] $cities */
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin / Villes</title>
</head>
<body>
<h1>Villes</h1>
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

</body>
</html>