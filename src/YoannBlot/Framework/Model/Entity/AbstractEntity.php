<?php
declare(strict_types=1);

namespace YoannBlot\Framework\Model\Entity;

use YoannBlot\Framework\Model\Entity\Common\IdPrimaryKey;
use YoannBlot\Framework\Model\Exception\DataBaseException;
use YoannBlot\Framework\Model\Repository\AbstractRepository;

/**
 * Class AbstractEntity.
 * All entities should extend this class.
 *
 * @package YoannBlot\Framework\Model\Entity
 * @author  Yoann Blot
 */
abstract class AbstractEntity
{

    const DEFAULT_ID = -1;

    use IdPrimaryKey;

    /**
     * @var array waiting links.
     */
    private $aLinks = [];

    /**
     * Add necessary links.
     */
    public function addLinks(): void
    {
        if (count($this->aLinks) > 0) {
            foreach ($this->aLinks as $sLinkName => $iLinkValue) {
                // TODO link Repository with entity
                $sRepositoryName = "YoannBlot\HouseFinder\Model\Repository\\" . ucfirst($sLinkName) . 'Repository';
                /** @var AbstractRepository $oRepository */
                $oRepository = new $sRepositoryName();
                try {
                    $oLinkedObject = $oRepository->get($iLinkValue);
                    if (null !== $oLinkedObject) {
                        $sLinkSetter = 'set' . ucfirst($sLinkName);
                        $this->$sLinkSetter($oLinkedObject);
                    }
                } catch (DataBaseException $e) {
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function __set($name, $value): void
    {
        $iIdPosition = strpos($name, '_id');
        if (!property_exists($this, $name) && false !== $iIdPosition) {
            $this->aLinks [substr($name, 0, $iIdPosition)] = intval($value);
        }
    }
}