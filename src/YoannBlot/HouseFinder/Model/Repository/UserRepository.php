<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Model\Repository;

use YoannBlot\Framework\Model\DataBase\Annotation\TableName;
use YoannBlot\Framework\Model\Repository\AbstractRepository;
use YoannBlot\HouseFinder\Model\Entity\User;

/**
 * Class UserRepository.
 *
 * @package YoannBlot\HouseFinder\Model\Repository
 * @author  Yoann Blot
 *
 * @TableName("user")
 *
 * @method User get(int $iId)
 * @method User[] getAll(string $sWhere = '', string $sOrderBy = '', int $iLimit = 0)
 */
class UserRepository extends AbstractRepository
{
}