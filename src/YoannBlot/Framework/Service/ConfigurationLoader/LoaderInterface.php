<?php
declare(strict_types=1);

namespace YoannBlot\Framework\Service\ConfigurationLoader;

/**
 * Interface LoaderInterface.
 *
 * @package YoannBlot\Framework\Service\ConfigurationLoader
 */
interface LoaderInterface
{
    /**
     * Load a configuration file.
     *
     * @param string $sFilePath file path.
     *
     * @return bool true if success, otherwise false.
     */
    public function load(string $sFilePath): bool;

    /**
     * Retrieve a configuration parameter.
     *
     * @param string $sParameterName parameter name
     * @param string $sSection section
     *
     * @return string matched parameter, or an empty string if not found.
     */
    public function get(string $sParameterName, string $sSection = 'GLOBAL'): string;

    /**
     * Get all parameters of given section.
     *
     * @param string $sSection section.
     *
     * @return array parameters.
     */
    public function getAll(string $sSection = 'GLOBAL'): array;
}