<?php

namespace YoannBlot\Framework\Model\Entity;

/**
 * Class AbstractEntity.
 * All entities should extend this class.
 *
 * @package YoannBlot\Framework\Model\Entity
 * @author  Yoann Blot
 */
abstract class AbstractEntity {

    const DEFAULT_ID = -1;

    /**
     * @var int id
     */
    protected $id = self::DEFAULT_ID;

    /**
     * @return int
     */
    public function getId (): int {
        return $this->id;
    }

    /**
     * @param int $iId
     */
    public function setId (int $iId) {
        $this->id = $iId;
    }

}