<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\SynchronizerToken\Test\Storage;

use Phalcon\Session\AdapterInterface;
use Volcanus\SynchronizerToken\Storage\PhalconSessionStorage;
use Volcanus\SynchronizerToken\Storage\StorageInterface;

/**
 * Test for PhalconSessionStorage
 *
 * @author k.holy74@gmail.com
 */
class PhalconSessionStorageTest extends \PHPUnit\Framework\TestCase
{

    public function setUp()
    {
        if (!extension_loaded('phalcon')) {
            $this->markTestSkipped('phalcon extension is not loaded.');
        }
        if (!class_exists(\Phalcon\Version::class)) {
            $this->markTestSkipped('needs \Phalcon\Version.');
        }
        if (version_compare(\Phalcon\Version::get(), '4.0.0', '>=')) {
            $this->markTestSkipped('A target of this test is Phalcon 3.');
        }
    }

    public function testImplementsStorageInterface()
    {
        /** @var $session AdapterInterface|\PHPUnit_Framework_MockObject_MockObject */
        $session = $this->createMock(AdapterInterface::class);

        $storage = new PhalconSessionStorage('storageName', $session);
        $this->assertInstanceOf(StorageInterface::class, $storage);
    }

    public function testGetAttributesReturnFromAdapter()
    {
        /** @var $session AdapterInterface|\PHPUnit_Framework_MockObject_MockObject */
        $session = $this->createMock(AdapterInterface::class);
        $session->expects($this->once())
            ->method('has')
            ->with($this->equalTo('storageName'))
            ->will($this->returnValue(true));
        $session->expects($this->once())
            ->method('get')
            ->with($this->equalTo('storageName'))
            ->will($this->returnValue(array('foo' => 'bar')));

        $storage = new PhalconSessionStorage('storageName', $session);
        $this->assertEquals(array('foo' => 'bar'), $storage->getAttributes());
    }

    public function testGetAttributesReturnEmptyArrayWhenAdapterNotHasTheAttribute()
    {
        /** @var $session AdapterInterface|\PHPUnit_Framework_MockObject_MockObject */
        $session = $this->createMock(AdapterInterface::class);
        $session->expects($this->once())
            ->method('has')
            ->will($this->returnValue(false));

        $storage = new PhalconSessionStorage('storageName', $session);
        $this->assertEquals(array(), $storage->getAttributes());
    }

    public function testSaveAttributesSetToAdapter()
    {
        $attributes = array('foo', 'bar', 'baz');

        /** @var $session AdapterInterface|\PHPUnit_Framework_MockObject_MockObject */
        $session = $this->createMock(AdapterInterface::class);
        $session->expects($this->once())
            ->method('set')
            ->with($this->equalTo('storageName'), $this->equalTo($attributes));

        $storage = new PhalconSessionStorage('storageName', $session);
        $storage->save($attributes);
    }

}
