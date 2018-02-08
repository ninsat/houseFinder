<?php

namespace YoannBlot\Framework\Service\DatabaseCreation;

/**
 * Trait TableServiceTrait.
 *
 * @package YoannBlot\Framework\Service\DatabaseCreation
 */
trait TableServiceTrait
{

    /**
     * @var TableService table service.
     */
    private $oTableService;

    /**
     * @return TableService table service.
     */
    protected function getTableService(): TableService
    {
        return $this->oTableService;
    }
}