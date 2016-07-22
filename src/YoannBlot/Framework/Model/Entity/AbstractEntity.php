<?php

namespace YoannBlot\Framework\Model\Entity;

/**
 * Class AbstractEntity.
 * All entities should extend this class.
 *
 * @package YoannBlot\Framework\Model\Entity
 */
abstract class AbstractEntity {

    /**
     * @var int id
     */
    protected $id;

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