<?php
declare(strict_types=1);

namespace YoannBlot\Framework\Service\DatabaseCreation;

/**
 * Trait RelationshipTrait.
 *
 * @package YoannBlot\Framework\Service\DatabaseCreation
 */
trait RelationshipTrait
{

    /**
     * @var RelationshipService relationship service.
     */
    private $oRelationshipService;

    /**
     * @return RelationshipService relationship service.
     */
    protected function getRelationshipService(): RelationshipService
    {
        return $this->oRelationshipService;
    }
}