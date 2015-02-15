<?php

/**
 * ZendDbHandler.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler\PersistenceHandler;

use ArrayIterator;
use Link0\Profiler\Exception;
use Link0\Profiler\PersistenceHandler;
use Link0\Profiler\PersistenceHandlerInterface;
use Link0\Profiler\ProfileInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterInterface as ZendDbAdapterInterface;
use Zend\Db\Sql\Ddl\Column\BigInteger;
use Zend\Db\Sql\Ddl\Column\Blob;
use Zend\Db\Sql\Ddl\Column\Varchar;
use Zend\Db\Sql\Ddl\Constraint\PrimaryKey;
use Zend\Db\Sql\Ddl\Constraint\UniqueKey;
use Zend\Db\Sql\Ddl\CreateTable;
use Zend\Db\Sql\Ddl\DropTable;
use Zend\Db\Sql\Sql;

/**
 * Zend\Db\Adapter implementation for Persistence
 *
 * @package Link0\Profiler\PersistenceHandler
 */
final class ZendDbHandler extends PersistenceHandler implements PersistenceHandlerInterface
{
    /**
     * @var \Zend\Db\Sql\Sql $sql
     */
    private $sql;

    /**
     * @var string $tableName
     */
    private $tableName = 'profile';

    /**
     * @var string $identifierColumn
     */
    private $identifierColumn = 'identifier';

    /**
     * @var string $dataColumn
     */
    private $dataColumn = 'data';

    /**
     * @param ZendDbAdapterInterface $adapter
     */
    public function __construct(ZendDbAdapterInterface $adapter)
    {
        parent::__construct();

        $this->sql = new Sql($adapter);
    }

    /**
     * @param Sql $sql
     */
    public function setSql(Sql $sql)
    {
        $this->sql = $sql;
    }

    /**
     * @return Sql $sql
     */
    public function getSql()
    {
        return $this->sql;
    }

    /**
     * @param string $tableName
     * @return PersistenceHandlerInterface $this
     */
    public function setTableName($tableName)
    {
        $this->tableName = $tableName;

        return $this;
    }

    /**
     * @return string $tableName
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * @param string $identifierColumn
     * @return PersistenceHandlerInterface $this
     */
    public function setIdentifierColumn($identifierColumn)
    {
        $this->identifierColumn = $identifierColumn;

        return $this;
    }

    /**
     * @return string $identifierColumn
     */
    public function getIdentifierColumn()
    {
        return $this->identifierColumn;
    }

    /**
     * @param string $dataColumn
     * @return PersistenceHandlerInterface $this
     */
    public function setDataColumn($dataColumn)
    {
        $this->dataColumn = $dataColumn;

        return $this;
    }

    /**
     * @return string $dataColumns
     */
    public function getDataColumn()
    {
        return $this->dataColumn;
    }

    /**
     * Returns a list of Identifier strings
     * Unfortunately the list() method is reserved
     *
     * @return string[]
     */
    public function getList()
    {
        $sql = $this->getSql();
        $select = $sql->select()
            ->columns(array($this->getIdentifierColumn()))
            ->from($this->getTableName())
            ->order(array('id' => 'asc'));

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        $identifiers = array();
        foreach($results as $result) {
            $identifiers[] = $result[$this->getIdentifierColumn()];
        }

        return $identifiers;
    }

    /**
     * @param  string $identifier
     * @throws Exception
     * @return ProfileInterface|null
     */
    public function retrieve($identifier)
    {
        $sql = $this->getSql();
        $select = $sql->select()
            ->from($this->getTableName())
            ->where(array($this->getIdentifierColumn() => $identifier));

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        $data = $this->getRetrieveObjectFromResults($results, $identifier);

        if($data === null) {
            return null;
        }

        return $this->getProfileFactory()->fromArray($this->getSerializer()->unserialize($data));
    }

    /**
     * @param ArrayIterator $results
     * @param string         $identifier
     * @throws Exception
     * @return string $data
     */
    private function getRetrieveObjectFromResults(ArrayIterator $results, $identifier)
    {
        $data = null;
        foreach($results as $result) {
            if($data === null) {
                $data = $result[$this->getDataColumn()];
            } else {
                throw new Exception('Multiple results for Profile[' . $this->getIdentifierColumn() . '=' . $identifier . '] found');
            }
        }

        return $data;
    }

    /**
     * @param  ProfileInterface $profile
     * @return PersistenceHandlerInterface
     */
    public function persist(ProfileInterface $profile)
    {
        $sql = $this->getSql();
        $insert = $sql->insert()
            ->into($this->getTableName())
            ->values(array(
                $this->getIdentifierColumn() => $profile->getIdentifier(),
                $this->getDataColumn() => $this->getSerializer()->serialize($profile->toArray()),
            ));

        $sql->prepareStatementForSqlObject($insert)->execute();

        return $this;
    }

    /**
     * Returns the table structure in Zend\Db\Column objects
     *
     * @return array
     */
    private function getTableStructure()
    {
        return array(
            'columns' => array(
                // Unique auto-incrementing primary key
                new BigInteger('id', false, null, array('auto_increment' => true, 'unsigned' => true)),

                // Identifier column
                new Varchar($this->getIdentifierColumn(), 64),

                // The blob column creates a length specification if(length), so length 0 is a nice hack to not specify length
                new Blob($this->getDataColumn(), 0)
            ),
            'constraints' => array(
                // Primary key and index constraints
                new PrimaryKey('id'),
                new UniqueKey(array($this->getIdentifierColumn()), $this->getIdentifierColumn())
            ),
        );
    }

    /**
     * Creates the table structure for you
     *
     * NOTE: This code should fully work when ZendFramework 2.4.0 is released, since then DDL supports auto_increment
     * @see https://github.com/zendframework/zf2/pull/6257
     *
     * @return PersistenceHandlerInterface
     */
    public function createTable()
    {
        $sql = $this->getSql();

        $createTable = new CreateTable($this->getTableName());
        $tableStructure = $this->getTableStructure();

        foreach($tableStructure['columns'] as $column) {
            $createTable->addColumn($column);
        }

        foreach($tableStructure['constraints'] as $constraint) {
            $createTable->addConstraint($constraint);
        }

        $sql->getAdapter()->query(
            $sql->getSqlStringForSqlObject($createTable),
            Adapter::QUERY_MODE_EXECUTE
        );

        return $this;
    }

    /**
     * Drops the table structure for you
     *
     * @return PersistenceHandlerInterface
     */
    public function dropTable()
    {
        $sql = $this->getSql();

        $dropTable = new DropTable($this->getTableName());

        $sql->getAdapter()->query($sql->getSqlStringForSqlObject($dropTable),
            Adapter::QUERY_MODE_EXECUTE
        );

        return $this;
    }
}
