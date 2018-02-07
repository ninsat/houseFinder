<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Model\Repository;

use YoannBlot\Framework\Model\DataBase\Annotation\TableName;
use YoannBlot\Framework\Model\Exception\EntityNotFoundException;
use YoannBlot\Framework\Model\Exception\QueryException;
use YoannBlot\Framework\Model\Repository\AbstractRepository;
use YoannBlot\HouseFinder\Model\Entity\City;
use YoannBlot\HouseFinder\Model\Entity\User;

/**
 * Class CityRepository
 *
 * @package YoannBlot\HouseFinder\Model\Repository
 * @author  Yoann Blot
 *
 * @TableName("houses_city")
 * @method City get(int $iId)
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
     * Retrieve only user available cities.
     *
     * @param User $oUser user.
     *
     * @return City[] available cities.
     */
    public function getAllAvailable(User $oUser): array
    {
        $sQuery = '';
        $sQuery .= " SELECT c.*";
        $sQuery .= " FROM " . $this->getTable() . ' c';
        $sQuery .= " INNER JOIN houses_user_city uc ON c.id = uc.city_id AND uc.user_id = :id";

        $this->getConnector()->setParameters([':id' => $oUser->getId()]);
        try {
            $aCities = $this->getConnector()->queryMultiple($sQuery, $this->getEntityClass());
        } catch (QueryException $e) {
            $aCities = [];
        }

        return $aCities;
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