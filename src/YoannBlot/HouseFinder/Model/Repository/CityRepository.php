<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Model\Repository;

use YoannBlot\Framework\Model\Exception\EntityNotFoundException;
use YoannBlot\Framework\Model\Exception\QueryException;
use YoannBlot\Framework\Model\Repository\AbstractRepository;
use YoannBlot\HouseFinder\Model\Entity\City;

/**
 * Class CityRepository
 *
 * @package YoannBlot\HouseFinder\Model\Repository
 * @author  Yoann Blot
 *
 * @table   houses_city
 */
class CityRepository extends AbstractRepository
{

    /**
     * @inheritDoc
     *
     * @return City[] matched cities.
     */
    public function getAll(string $sWhere = 'WHERE enabled = 1', string $sOrderBy = 'name asc', int $iLimit = 0): array
    {
        return parent::getAll($sWhere, $sOrderBy, $iLimit);
    }

    /**
     * Get a city from its postal code.
     *
     * @param string $sPostalCode postal code.
     *
     * @return null|City found city, or null.
     */
    public function getOneByPostalCode(string $sPostalCode): ?City
    {
        $sQuery = '';
        $sQuery .= "SELECT * FROM " . $this->getTable() . " WHERE lower(postal_code) = lower(:postalCode) LIMIT 1";

        $this->getConnector()->setParameters([':postalCode' => $sPostalCode]);
        try {
            /** @var City $oCity */
            $oCity = $this->getConnector()->querySingle($sQuery, $this->getEntityClass());
        } catch (EntityNotFoundException $oException) {
            $this->getLogger()->info("City not found with postal code '$sPostalCode'.");
            $oCity = null;
        } catch (QueryException $oException) {
            $this->getLogger()->error("Query exception : " . $oException->getMessage());
        }

        return $oCity;
    }
}