<?php
declare(strict_types=1);

namespace YoannBlot\Framework\Service\ConfigurationLoader;

/**
 * Class LoaderService.
 *
 * @package YoannBlot\Framework\Service\ConfigurationLoader
 */
class LoaderService implements LoaderInterface
{
    /**
     * @var array parameters.
     */
    private $aParameters = null;

    /**
     * @inheritdoc
     */
    public function load(string $sFilePath): bool
    {
        $this->aParameters = [];

        if (is_file($sFilePath)) {
            $this->aParameters = parse_ini_file($sFilePath, true);
        }

        return false !== $this->aParameters;
    }

    /**
     * @inheritdoc
     */
    public function get(string $sParameterName, string $sSection = 'GLOBAL'): string
    {
        $sParameterValue = '';
        if (array_key_exists($sSection, $this->aParameters)) {
            $sParameterValue = $this->aParameters[$sSection][$sParameterName];
        }

        return $sParameterValue;
    }

    /**
     * @inheritdoc
     */
    public function getAll(string $sSection = 'GLOBAL'): array
    {
        $aParameters = [];
        if (array_key_exists($sSection, $this->aParameters)) {
            $aParameters = $this->aParameters[$sSection];
        }

        return $aParameters;
    }


}