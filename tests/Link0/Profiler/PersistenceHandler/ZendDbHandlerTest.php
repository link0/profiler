<?php

/**
 * ZendDbHandlerTest.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler\PersistenceHandler;
use Mockery as M;

/**
 * ZendDbHandlerTest
 *
 * @package Link0\Profiler\PersistenceHandler
 */
class ZendDbHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ZendDbHandler $handler
     */
    protected $handler;

    public function setUp()
    {
        $mocks = $this->getMocks();
        $this->handler = new ZendDbHandler($mocks['adapter'], $mocks['platform']);
    }

    public function testAdapterInterface()
    {
        $this->assertInstanceOf('Zend\Db\Adapter\AdapterInterface', $this->handler->getAdapter());
    }

    public function testOverridableDefaultValues()
    {
        $this->assertEquals('profile', $this->handler->getTableName());
        $this->assertEquals('data', $this->handler->getDataColumn());
        $this->assertEquals('identifier', $this->handler->getIdentifierColumn());

        $this->handler->setTableName('profileFoo');
        $this->handler->setDataColumn('dataFoo');
        $this->handler->setIdentifierColumn('identifierFoo');

        $this->assertEquals('profileFoo', $this->handler->getTableName());
        $this->assertEquals('dataFoo', $this->handler->getDataColumn());
        $this->assertEquals('identifierFoo', $this->handler->getIdentifierColumn());
    }

    public function testGetList()
    {
        $this->handler->getList();
    }

    /**
     * Method to mock Zend\Db\Adapter\AdapterInterface
     * Derived from https://gist.github.com/robertbasic/3717485
     *
     * @return \Zend\Db\Adapter\AdapterInterface
     */
    protected function getMocks()
    {
        $adapter = m::mock('Zend\Db\Adapter\AdapterInterface');
        $driver = m::mock('Zend\Db\Adapter\Driver\DriverInterface');
        $platform = m::mock('Zend\Db\Adapter\Platform\Mysql[getName]');
        $sqlPlatform = m::mock('Zend\Db\Sql\PlatformInterface');

        $stmt = m::mock('Zend\Db\Adapter\Driver\Pdo\StatementContainerInterface');
        $paramContainer = m::mock('Zend\Db\Adapter\ParameterContainer');

        $platform->shouldReceive('getName')
            ->once()
            ->andReturn('MySQL');

        $stmt->shouldReceive('getParameterContainer')
            ->once()
            ->andReturn($paramContainer);

        $stmt->shouldReceive('setSql')
            ->once()
            ->andReturn($stmt);

        $stmt->shouldReceive('execute')
            ->once()
            ->andReturn(array());

        $adapter->shouldReceive('getDriver')
            ->once()
            ->andReturn($driver);

        $adapter->shouldReceive('getPlatform')
            ->once()
            ->andReturn($platform);

        $adapter->shouldReceive('createStatement')
            ->once()
            ->andReturn($stmt);

        $driver->shouldReceive('createStatement')
            ->once()
            ->andReturn($stmt);

        return array(
            'adapter' => $adapter,
            'platform' => $sqlPlatform,
        );
    }
}