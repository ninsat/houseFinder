<?php
declare(strict_types=1);

namespace YoannBlot\Framework\Command\Database;

use YoannBlot\Framework\Command\AbstractCommand;
use YoannBlot\Framework\Model\Repository\AbstractRepository;
use YoannBlot\Framework\Service\DatabaseCreation\StructureService;
use YoannBlot\Framework\Service\DatabaseCreation\StructureTrait;
use YoannBlot\Framework\Service\DatabaseCreation\TableService;
use YoannBlot\Framework\Service\DatabaseCreation\TableServiceTrait;
use YoannBlot\Framework\Service\Logger\LoggerService;

/**
 * Class Database\StructureUpdateCommand.
 *
 * @package YoannBlot\Framework\Command\Database
 */
class StructureUpdateCommand extends AbstractCommand
{

    use StructureTrait, TableServiceTrait;

    /**
     * @var AbstractRepository[] repositories.
     */
    private $aRepositories = [];

    /**
     * AbstractCommand constructor.
     *
     * @param LoggerService $oLogger logger.
     * @param TableService $oTableService table service.
     * @param StructureService $oStructureService structure service.
     * @param AbstractRepository[] $repositoryServicesList all repositories.
     */
    public function __construct(
        LoggerService $oLogger,
        TableService $oTableService,
        StructureService $oStructureService,
        array $repositoryServicesList
    ) {
        parent::__construct($oLogger);
        $this->aRepositories = $repositoryServicesList;
        $this->oTableService = $oTableService;
        $this->oStructureService = $oStructureService;
    }

    /**
     * @inheritdoc
     */
    public function run(): bool
    {
        return $this->createNormalTables() && $this->createManyToManyTables();
    }

    /**
     * Create normal tables.
     *
     * @return bool true if success, otherwise false.
     */
    private function createNormalTables(): bool
    {
        $bSuccess = true;
        foreach ($this->aRepositories as $oRepository) {
            $this->getLogger()->info("Repository " . get_class($oRepository) . " => table '{$oRepository->getTable()}'.");
            $oTable = $this->getStructureService()->getTable($oRepository);
            if (!$this->getTableService()->exists($oTable->getName()) && !$this->getTableService()->create($oTable)) {
                $this->getLogger()->error("Error creating table '{$oTable->getName()}'.");
                $bSuccess = false;
            }
        }

        return $bSuccess;
    }

    /**
     * Create many to many tables.
     *
     * @return bool true if success, otherwise false.
     */
    private function createManyToManyTables(): bool
    {
        $bSuccess = true;
        foreach ($this->getStructureService()->getManyToManyTables() as $oTable) {
            if (!$this->getTableService()->exists($oTable->getName()) && !$this->getTableService()->create($oTable)) {
                $this->getLogger()->error("Error creating table '{$oTable->getName()}'.");
                $bSuccess = false;
            }
        }

        return $bSuccess;
    }

}