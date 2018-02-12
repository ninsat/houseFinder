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
 * @TableName("city")
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
        $sQuery .= " FROM " . $this->getTableName() . ' c';
        $sQuery .= " INNER JOIN user_city uc ON c.id = uc.city_id AND uc.user_id = :id";

        $this->getConnector()->setParameters([':id' => $oUser->getId()]);

        return $this->getRelationshipService()->getEntities($sQuery, $this->getEntityClass());
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
        $sQuery .= " SELECT * ";
        $sQuery .= " FROM " . $this->getTableName();
        $sQuery .= " WHERE lower(postal_code) = lower(:postalCode)";
        $sQuery .= " LIMIT 1";

        $this->getConnector()->setParameters([':postalCode' => $sPostalCode]);
        try {
            /** @var City $oCity */
            $oCity = $this->getRelationshipService()->getEntity($sQuery, $this->getEntityClass());
        } catch (EntityNotFoundException $oException) {
            $this->getLogger()->info("City not found with postal code '$sPostalCode'.");
            $oCity = null;
        } catch (QueryException $oException) {
            $this->getLogger()->error("Query exception : " . $oException->getMessage());
        }

        return $oCity;
    }

    /**
     * Get a city from its name.
     *
     * @param string $sName name.
     *
     * @return null|City found city, or null.
     */
    public function getOneByName(string $sName): ?City
    {
        $sQuery = '';
        $sQuery .= " SELECT * ";
        $sQuery .= " FROM " . $this->getTableName();
        $sQuery .= " WHERE lower(name) = lower(:name)";
        $sQuery .= " LIMIT 1";

        $this->getConnector()->setParameters([':name' => $sName]);
        try {
            /** @var City $oCity */
            $oCity = $this->getRelationshipService()->getEntity($sQuery, $this->getEntityClass());
        } catch (EntityNotFoundException $oException) {
            $this->getLogger()->info("City not found with name '$sName'.");
            $oCity = null;
        } catch (QueryException $oException) {
            $this->getLogger()->error("Query exception : " . $oException->getMessage());
        }

        return $oCity;
    }
}