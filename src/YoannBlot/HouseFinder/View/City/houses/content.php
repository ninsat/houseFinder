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
<ul class="houses">
    <?php foreach ($houses as $position => $house): ?>
        <li>
            <a href="/house/<?= $house->getId(); ?>">
                <figure>
                    <img src="<?= $house->getMainImage(); ?>" alt="<?= $house->getTitle(); ?>"/>
                    <figcaption>
                        <mark class="position"><?= $position + 1; ?></mark>
                        <time datetime="<?= $house->getDate()->format(DATE_ATOM); ?>">
                            Ajouté le <?= $house->getDate()->format('d/m/Y'); ?>
                        </time>
                        <span class="rent"><?= $house->getRent(); ?>&euro;</span>
                        <span class="surface"><?= $house->getSurface(); ?> m²</span>
                    </figcaption>
                </figure>
                <h3><?= $house->getTitle(); ?></h3>
            </a>
        </li>
    <?php endforeach; ?>
</ul>