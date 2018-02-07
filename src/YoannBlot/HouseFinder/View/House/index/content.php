<?php
/** @var \YoannBlot\HouseFinder\Model\Entity\House $house */
?>
<h1><?= $house->getTitle(); ?></h1>
<section class="images">
    <ul>
        <?php foreach ($house->getImages() as $iCountImage => $sImage): ?>
            <img src="<?= $sImage; ?>" alt="image #<?= $iCountImage; ?> <?= $house->getTitle(); ?>"/>
        <?php endforeach; ?>
    </ul>
</section>

<p><?= nl2br($house->getDescription()); ?></p>
<h2>Tarifs</h2>
<dl>
    <dt>Loyer</dt>
    <dd><?= $house->getRent(); ?></dd>
    <dt>Frais d'agence</dt>
    <dd><?= $house->getFees(); ?></dd>
    <dt>Garantie</dt>
    <dd><?= $house->getGuarantee(); ?></dd>
</dl>
<h2>Détails</h2>
<dl>
    <dt>Nombre de pièces</dt>
    <dd><?= $house->getPieces(); ?></dd>
    <dt>Nombre de chambres</dt>
    <dd><?= $house->getBedrooms(); ?></dd>
    <dt>Superficie</dt>
    <dd><?= $house->getSurface(); ?>m2</dd>
</dl>
<p>Autres appartements à <?= $house->getCity()->getName(); ?></p>