<?php
declare(strict_types=1);

namespace YoannBlot\Framework\Model\DataBase\Annotation;

/**
 * Class ManyToMany.
 *
 * @package YoannBlot\Framework\Model\DataBase\Annotation
 * @Annotation
 * @Target({"PROPERTY"})
 */
final class ManyToMany
{
    /**
     * @var string
     */
    public $table;

    /**
     * @var string
     */
    public $current_id;

    /**
     * @var string
     */
    public $foreign_id;
}