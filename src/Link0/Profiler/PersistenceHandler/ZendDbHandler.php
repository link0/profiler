<?php

/**
 * ZendDbHandler.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler\PersistenceHandler;

use Link0\Profiler\Exception;
use Link0\Profiler\PersistenceHandler;
use Link0\Profiler\PersistenceHandlerInterface;
use Link0\Profiler\Profile;
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
     * @var \Zend\Db\Adapter\AdapterInterface $adapter
     */
    private $adapter = null;

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
        $this->adapter = $adapter;
    }

    /**
     * @return ZendDbAdapterInterface
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * @param string $tableName
     * @return ZendDbAdapter $this
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
     * @return ZendDbAdapter $this
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
     * @return ZendDbHandler $this
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
        $adapter = $this->getAdapter();

        $sql = new Sql($adapter);
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
     * @throws \Link0\Profiler\Exception
     * @return Profile|null $profile
     */
    public function retrieve($identifier)
    {
        $adapter = $this->getAdapter();

        $sql = new Sql($adapter);
        $select = $sql->select()
            ->from($this->getTableName())
            ->where(array($this->getIdentifierColumn() => $identifier));

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        $data = null;
        foreach($results as $result) {
            if($data === null) {
                $data = $result[$this->getDataColumn()];
            } else {
                throw new Exception("Multiple results for Profile[{$this->getIdentifierColumn()}='{$identifier}'] found");
            }
        }
        return unserialize($data);
    }

    /**
     * @param  Profile $profile
     * @return PersistenceHandlerInterface $this
     */
    public function persist(Profile $profile)
    {
        $sql = new Sql($this->getAdapter());

        $insert = $sql->insert()
            ->into($this->getTableName())
            ->values(array(
                $this->getIdentifierColumn() => $profile->getIdentifier(),
                $this->getDataColumn() => serialize($profile),
            ));

        $sql->prepareStatementForSqlObject($insert)->execute();

        return $this;
    }

    /**
     * Creates the table structure for you
     *
     * NOTE: This code should fully work when ZendFramework 2.4.0 is released, since then DDL supports auto_increment
     * @see https://github.com/zendframework/zf2/pull/6257
     */
    public function createTable()
    {
        $adapter = $this->getAdapter();
        $createTable = new CreateTable($this->getTableName());

        // Unique auto-incrementing primary key
        $createTable->addColumn(new BigInteger('id', false, null, array('auto_increment' => true, 'unsigned' => true)));

        // Identifier column
        $createTable->addColumn(new Varchar($this->getIdentifierColumn(), 64));

        // The blob column creates a length specification if(length), so length 0 is a nice hack to not specify length
        $createTable->addColumn(new Blob($this->getDataColumn(), 0));

        // Primary key and index constraints
        $createTable->addConstraint(new PrimaryKey('id'));
        $createTable->addConstraint(new UniqueKey(array($this->getIdentifierColumn()), $this->getIdentifierColumn()));

        $sql = new Sql($adapter);
        $adapter->query(
            $sql->getSqlStringForSqlObject($createTable),
            $adapter::QUERY_MODE_EXECUTE
        );
    }

    /**
     * Drops the table structure for you
     */
    public function dropTable()
    {
        $adapter = $this->getAdapter();
        $dropTable = new DropTable($this->getTableName());

        $sql = new Sql($adapter);
        $adapter->query($sql->getSqlStringForSqlObject($dropTable),
            $adapter::QUERY_MODE_EXECUTE
        );
    }
}
