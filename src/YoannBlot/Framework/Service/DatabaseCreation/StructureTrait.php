<?php

namespace YoannBlot\Framework\Service\DatabaseCreation;

/**
 * Trait StructureTrait.
 *
 * @package YoannBlot\Framework\Service\DatabaseCreation
 */
trait StructureTrait
{

    /**
     * @var StructureService structure service.
     */
    private $oStructureService;

    /**
     * @return StructureService structure service.
     */
    protected function getStructureService(): StructureService
    {
        return $this->oStructureService;
    }
}