<?php

/**
 * ZendDbHandlerTest.php
 *
 * @author Dennis de Greef <github@link0.net>
 */
namespace Link0\Profiler\PersistenceHandler;

use Link0\Profiler\Profile;
use Mockery as M;
use Zend\Db\Sql\Sql;

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
    private $handler;

    /**
     * @var StatementInterface $statement
     */
    private $statement;

    /**
     * Setup mocks
     */
    public function setUp()
    {
        // Zend\Db\Adapter\Platform\PlatformInterface
        $adapterPlatformAdapter = m::mock('Zend\Db\Adapter\Platform\PlatformInterface');
        $adapterPlatformAdapter->shouldReceive('getName')
            ->once()
            ->andReturn('mysql');

        // Zend\Db\Sql\Platform\AbstractPlatform
        $adapterAbstractPlatform = m::mock('Zend\Db\Sql\Platform\AbstractPlatform');
        $adapterAbstractPlatform->shouldReceive('getDriver')
            ->once()
            ->andReturn();
        $adapterAbstractPlatform->shouldReceive('setSubject')
            ->once()
            ->andReturnNull();
        $adapterAbstractPlatform->shouldReceive('prepareStatement')
            ->once()
            ->andReturnNull();
        $adapterAbstractPlatform->shouldReceive('getSqlString')
            ->once()
            ->andReturn('');

        // Zend\Db\Adapter\Driver\StatementInterface
        $this->statement = m::mock('Zend\Db\Adapter\Driver\StatementInterface');
        $this->statement->shouldReceive('createStatement')
            ->once()
            ->andReturnSelf();

        // Zend\Db\Adapter\AdapterInterface
        $adapter = m::mock('Zend\Db\Adapter\AdapterInterface');
        $adapter->shouldReceive('getPlatform')
            ->once()
            ->andReturn($adapterPlatformAdapter);
        $adapter->shouldReceive('getDriver')
            ->once()
            ->andReturn($this->statement);
        $adapter->shouldReceive('query')
            ->once()
            ->andReturn();

        // Initializing objects
        $sql = new Sql($adapter, null, $adapterAbstractPlatform);
        $this->handler = new ZendDbHandler($adapter);
        $this->handler->setSql($sql);
    }

    public function testOverriddenDefaultValues()
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

    /**
     * Tests the getList method
     */
    public function testGetList()
    {
        // Zend\Db\Adapter\Driver\ResultInterface
        $resultInterface = m::mock('Zend\Db\Adapter\Driver\ResultInterface');
        $resultInterface->shouldReceive('rewind')->withNoArgs()->once();
        $resultInterface->shouldReceive('valid')->withNoArgs()->times(1)->andReturn(1);
        $resultInterface->shouldReceive('current')->withNoArgs()->times(1);
        $resultInterface->shouldReceive('next')->withNoArgs()->times(1)->andReturn('Foo');
        $resultInterface->shouldReceive('valid')->withNoArgs()->times(1)->andReturn(0);

        $this->statement->shouldReceive('execute')
            ->once()
            ->andReturn($resultInterface);

        $list = $this->handler->getList();
        $this->assertNotEmpty($list);
        $this->assertTrue(sizeof($list) === 1);
        $this->assertContains(null, $list); // This is weird, since next->andReturn('Foo') should set the element accordingly
    }

    public function testPersist()
    {
        // Zend\Db\Adapter\Driver\ResultInterface
        $resultInterface = m::mock('Zend\Db\Adapter\Driver\ResultInterface');
        $resultInterface->shouldReceive('rewind')->withNoArgs()->once();
        $resultInterface->shouldReceive('valid')->withNoArgs()->times(1)->andReturn(1);
        $resultInterface->shouldReceive('current')->withNoArgs()->times(1);
        $resultInterface->shouldReceive('next')->withNoArgs()->times(1)->andReturn('Foo');
        $resultInterface->shouldReceive('valid')->withNoArgs()->times(1)->andReturn(0);

        $this->statement->shouldReceive('execute')
            ->once()
            ->andReturn($resultInterface);

        $profile = Profile::create();
        $this->assertSame($this->handler, $this->handler->persist($profile));
    }

    public function testRetrieveNull()
    {
        $resultInterface = new \ArrayIterator(array());

        $this->statement->shouldReceive('execute')
            ->once()
            ->andReturn($resultInterface);

        $this->assertNull($this->handler->retrieve('foo'));
    }

    public function testRetrieveObject()
    {
        $profile = Profile::create();
        $resultInterface = new \ArrayIterator(array(
            array('identifier' => $profile->getIdentifier(), 'data' => serialize($profile->toArray())),
        ));

        $this->statement->shouldReceive('execute')
            ->once()
            ->andReturn($resultInterface);

        $unserializedProfile = $this->handler->retrieve('foo');
        $this->assertInstanceOf('Link0\Profiler\Profile', $unserializedProfile);
        $this->assertEquals($profile->getIdentifier(), $unserializedProfile->getIdentifier());
    }

    /**
     * @expectedException \Link0\Profiler\Exception
     * @expectedExceptionMessage Multiple results for Profile[identifier=foo] found
     */
    public function testRetrieveMultiple()
    {
        $resultInterface = new \ArrayIterator(array(
            array('identifier' => 'foo', 'data' => 'bar'),
            array('identifier' => 'baz', 'data' => 'boo'),
        ));

        $this->statement->shouldReceive('execute')
            ->once()
            ->andReturn($resultInterface);

        $this->handler->retrieve('foo');
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Unable to unserialize Profile for data 'bar'
     */
    public function testRetrieveInvalidSerialization()
    {
        $resultInterface = new \ArrayIterator(array(
            array('identifier' => 'foo', 'data' => 'bar'),
        ));

        $this->statement->shouldReceive('execute')
            ->once()
            ->andReturn($resultInterface);

        $this->handler->retrieve('foo');
    }

    /**
     * Tests the create table method
     */
    public function testCreateTable()
    {
        $this->assertSame($this->handler, $this->handler->createTable());
    }

    /**
     * Tests the drop table method
     */
    public function testDropTable()
    {
        $this->assertSame($this->handler, $this->handler->dropTable());
    }
}