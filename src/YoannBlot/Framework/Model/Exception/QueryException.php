<?php
declare(strict_types=1);

namespace YoannBlot\Framework\Model\Exception;

/**
 * Class QueryException.
 *
 * @package YoannBlot\Framework\Model\Exception
 * @author  Yoann Blot
 */
class QueryException extends DataBaseException {

    /**
     * QueryException constructor.
     *
     * @param string $sQuery      query that failed.
     * @param string $sErrorCause error cause.
     * @param int    $iErrorCode  error code.
     */
    public function __construct (string $sQuery, string $sErrorCause, int $iErrorCode) {
        parent::__construct("Failed to execute SQL query, cause ($iErrorCode) : $sErrorCause." . PHP_EOL . $sQuery);
    }
}