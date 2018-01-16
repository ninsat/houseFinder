<?php
declare(strict_types=1);

namespace YoannBlot\Framework\Service\Logger;

use Psr\Log\LoggerInterface;

/**
 * Class LoggerTrait.
 *
 * @package YoannBlot\Framework\Service\Logger
 */
trait LoggerTrait
{

    /**
     * @var LoggerService logger.
     */
    protected $oLogger = null;

    /**
     * @return LoggerService logger.
     */
    protected function getLogger(): LoggerInterface
    {
        return $this->oLogger;
    }
}