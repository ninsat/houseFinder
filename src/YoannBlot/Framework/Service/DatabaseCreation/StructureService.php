<?php

namespace YoannBlot\Framework\Service\DatabaseCreation;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\AnnotationReader;
use YoannBlot\Framework\Model\DataBase\Annotation\AutoIncrement;
use YoannBlot\Framework\Model\DataBase\Annotation\DefaultValue;
use YoannBlot\Framework\Model\DataBase\Annotation\Length;
use YoannBlot\Framework\Model\DataBase\Annotation\ManyToMany;
use YoannBlot\Framework\Model\DataBase\Annotation\Nullable;
use YoannBlot\Framework\Model\DataBase\Annotation\PrimaryKey;
use YoannBlot\Framework\Model\DataBase\ForeignKeyColumn;
use YoannBlot\Framework\Model\DataBase\ManyToManyColumn;
use YoannBlot\Framework\Model\DataBase\TableColumn;
use YoannBlot\Framework\Model\DataBase\TableStructure;
use YoannBlot\Framework\Model\Entity\AbstractEntity;
use YoannBlot\Framework\Model\Exception\DataBaseException;
use YoannBlot\Framework\Model\Repository\AbstractRepository;
use YoannBlot\Framework\Service\Logger\LoggerService;
use YoannBlot\Framework\Service\Logger\LoggerTrait;
use YoannBlot\Framework\Service\Repository\FactoryService;
use YoannBlot\Framework\Service\Repository\RepositoryFactoryTrait;

/**
 * Class StructureService.
 *
 * @package YoannBlot\Framework\Service\DatabaseCreation
 */
class StructureService
{
    use LoggerTrait, RepositoryFactoryTrait;

    /**
     * @var TableStructure[] tables structure.
     */
    private $aTables = [];

    /**
     * @var AnnotationReader annotation reader.
     */
    private $oAnnotationReader = null;

    /**
     * StructureService constructor.
     *
     * @param LoggerService $oLogger logger.
     * @param FactoryService $oRepositoryService repository factory service.
     */
    public function __construct(LoggerService $oLogger, FactoryService $oRepositoryService)
    {
        $this->oLogger = $oLogger;
        $this->oRepositoryFactoryService = $oRepositoryService;
    }

    /**
     * Get all many to many tables.
     *
     * @return TableStructure[] many to many tables.
     */
    public function getManyToManyTables(): array
    {
        $aManyToManyTables = [];
        foreach ($this->aTables as $oCurrentTable) {
            foreach ($oCurrentTable->getManyToManyColumns() as $oColumn) {
                $oForeignRepository = $this->getRepositoryFactoryService()->getRepository($oColumn->getName());
                if (null === $oForeignRepository) {
                    $this->getLogger()->error("Cannot create ManyToMany table between " . $oCurrentTable->getName() . ' and ' . $oColumn->getName());
                } else {
                    $oForeignTable = $this->getTable($oForeignRepository);
                    $oCurrentColumn = new ForeignKeyColumn(
                        $oCurrentTable->getName() . '_id',
                        $oCurrentTable->getName(),
                        $oCurrentTable->getPrimaryKeys()[0]
                    );
                    $oCurrentColumn->setPrimaryKey(true);
                    $oForeignColumn = new ForeignKeyColumn(
                        $oForeignTable->getName() . '_id',
                        $oForeignTable->getName(),
                        $oForeignTable->getPrimaryKeys()[0]
                    );
                    $oForeignColumn->setPrimaryKey(true);
                    $aManyToManyTables[] = new TableStructure($oCurrentTable->getName() . '_' . $oForeignTable->getName(),
                        [$oCurrentColumn, $oForeignColumn]);
                }
            }
        }

        return $aManyToManyTables;
    }

    /**
     * Get a table structure.
     *
     * @param AbstractRepository $oRepository repository.
     *
     * @return TableStructure table structure.
     */
    public function getTable(AbstractRepository $oRepository): TableStructure
    {
        if (!array_key_exists($oRepository->getTable(), $this->aTables)) {
            $this->loadTable($oRepository);
        }

        return $this->aTables[$oRepository->getTable()];
    }

    /**
     * Load and save table structure.
     *
     * @param AbstractRepository $oRepository repository.
     */
    private function loadTable(AbstractRepository $oRepository): void
    {
        $this->aTables[$oRepository->getTable()] = new TableStructure(
            $oRepository->getTable(),
            $this->getColumns($oRepository)
        );
    }

    /**
     * Get all columns in given repository.
     *
     * @param AbstractRepository $oRepository repository.
     *
     * @return TableColumn[] columns.
     */
    private function getColumns(AbstractRepository $oRepository): array
    {
        $aColumns = [];
        $sEntityClass = $oRepository->getEntityClass();
        try {
            $oReflection = new \ReflectionClass($sEntityClass);
            foreach ($oReflection->getProperties() as $oProperty) {
                if ($this->hasAnnotation($oProperty, ManyToMany::class)) {
                    $sEntityType = $this->getVariableType($oProperty);
                    $aColumns[] = new ManyToManyColumn($sEntityType, $sEntityType);
                } else {
                    $oColumn = $this->getColumn($oProperty);
                    if (null !== $oColumn) {
                        $aColumns [] = $oColumn;
                    }
                }
            }
        } catch (\ReflectionException $oException) {
            $this->getLogger()->error("Cannot load entity class '$sEntityClass'. " . $oException->getMessage());
        }

        return $aColumns;
    }

    /**
     * Check if given property contains annotation.
     *
     * @param \ReflectionProperty $oProperty property to check.
     * @param string $sAnnotationClass annotation class.
     *
     * @return bool true if property contains annotation.
     */
    private function hasAnnotation(\ReflectionProperty $oProperty, string $sAnnotationClass): bool
    {
        return null !== $this->getAnnotationReader()->getPropertyAnnotation($oProperty, $sAnnotationClass);
    }

    /**
     * @return AnnotationReader annotation reader.
     */
    private function getAnnotationReader(): AnnotationReader
    {
        if (null === $this->oAnnotationReader) {
            try {
                $this->oAnnotationReader = new AnnotationReader();
            } catch (AnnotationException $oException) {
                $this->getLogger()->error("Cannot create annotation reader...");
            }
        }
        return $this->oAnnotationReader;
    }

    /**
     * Get the right variable type.
     *
     * @param \ReflectionProperty $oProperty property to retrieve variable.
     *
     * @return string variable type.
     */
    private function getVariableType(\ReflectionProperty $oProperty): string
    {
        $sVariableType = substr($oProperty->getDocComment(),
            strpos($oProperty->getDocComment(), '@var ') + strlen('@var '));
        $sVariableType = substr($sVariableType, 0, strpos($sVariableType, ' '));
        $iBracketPosition = strpos($sVariableType, '[]');
        if (false !== $iBracketPosition) {
            $sVariableType = substr($sVariableType, 0, $iBracketPosition);
        }

        return $sVariableType;
    }

    /**
     * Get column from reflection property.
     *
     * @param \ReflectionProperty $oProperty property.
     *
     * @return TableColumn|null column.
     */
    private function getColumn(\ReflectionProperty $oProperty): ?TableColumn
    {
        $oColumn = null;
        try {
            if ($this->isForeignKey($oProperty)) {
                $oRepository = $this->getRepositoryFactoryService()->getRepository($this->getVariableType($oProperty));
                $oForeignTable = $this->getTable($oRepository);
                $oColumn = new ForeignKeyColumn(
                    $oProperty->getName() . AbstractEntity::FOREIGN_KEY_SUFFIX,
                    $oForeignTable->getName(),
                    $oForeignTable->getPrimaryKeys()[0]
                );
            } else {
                $bPrimary = $this->hasAnnotation($oProperty, PrimaryKey::class);
                if ($bPrimary) {
                    $bNullable = false;
                } else {
                    /** @var Nullable $oNullableAnnotation */
                    $oNullableAnnotation = $this->getAnnotationReader()->getPropertyAnnotation($oProperty,
                        Nullable::class);
                    $bNullable = (null === $oNullableAnnotation) || $oNullableAnnotation->isNullable();
                }

                /** @var DefaultValue $oDefaultValueAnnotation */
                $oDefaultValueAnnotation = $this->getAnnotationReader()->getPropertyAnnotation($oProperty,
                    DefaultValue::class);
                $sDefaultValue = (null !== $oDefaultValueAnnotation) ? $oDefaultValueAnnotation->getValue() : null;

                $oColumn = new TableColumn(
                    $oProperty->getName(),
                    $this->getSqlType($oProperty),
                    $bNullable,
                    $sDefaultValue,
                    $this->hasAnnotation($oProperty, AutoIncrement::class),
                    $bPrimary
                );
            }
        } catch (\Exception $e) {
            $oColumn = null;
        }

        return $oColumn;
    }

    /**
     * Check if given property is a foreign key.
     *
     * @param \ReflectionProperty $oProperty property.
     *
     * @return bool true if it's a foreign key, otherwise false.
     */
    private function isForeignKey(\ReflectionProperty $oProperty): bool
    {
        $bSuccess = false;
        $sEntityType = $this->getVariableType($oProperty);
        if (class_exists($sEntityType) && null !== $this->getRepositoryFactoryService()->getRepository($sEntityType)) {
            $bSuccess = true;
        }

        return $bSuccess;
    }

    /**
     * Get the SQL column type from property.
     *
     * @param \ReflectionProperty $oProperty
     *
     * @return string SQL column type.
     *
     * @throws DataBaseException
     */
    private function getSqlType(\ReflectionProperty $oProperty): string
    {
        // get length
        $iLength = null;
        /** @var Length $oLengthAnnotation */
        $oLengthAnnotation = $this->getAnnotationReader()->getPropertyAnnotation($oProperty, Length::class);
        if (null !== $oLengthAnnotation) {
            $iLength = $oLengthAnnotation->getLength();
        }

        // get SQL type
        $sVariableType = $this->getVariableType($oProperty);
        $sSqlType = null;
        switch ($sVariableType) {
            case 'bool':
            case 'boolean':
                $sSqlType = 'TINYINT(1) UNSIGNED';
                break;
            case 'int':
                if (null === $iLength) {
                    $iLength = 9;
                }
                if ($iLength <= 2) {
                    $sSqlType = 'tinyint';
                } elseif ($iLength <= 4) {
                    $sSqlType = 'smallint';
                } elseif ($iLength <= 7) {
                    $sSqlType = 'mediumint';
                } elseif ($iLength <= 9) {
                    $sSqlType = 'int';
                } else {
                    $sSqlType = 'bigint';
                }

                $sSqlType = "$sSqlType($iLength) unsigned";
                break;
            case 'float':
                // TODO decimal, float or double
                $sSqlType = "DOUBLE";
                break;
            case '\DateTime':
                $sSqlType = "DATETIME";
                break;
            case 'string':
                if (null === $iLength) {
                    $iLength = 255;
                }
                if ($iLength < 1000) {
                    $sSqlType = "VARCHAR($iLength)";
                } else {
                    $sSqlType = "TEXT";
                }
                break;
            default :
                $this->getLogger()->error($oProperty->getDeclaringClass()->getName() . " => Unknown SQL type for column @var = $sVariableType.");
                throw new DataBaseException("Invalid SQL type found for column @var = $sVariableType.");
        }

        return $sSqlType;
    }
}