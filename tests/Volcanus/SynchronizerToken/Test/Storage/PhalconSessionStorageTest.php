<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\SynchronizerToken\Test\Storage;

use Volcanus\SynchronizerToken\Storage\PhalconSessionStorage;

/**
 * Test for PhalconSessionStorage
 *
 * @author k.holy74@gmail.com
 */
class PhalconSessionStorageTest extends \PHPUnit_Framework_TestCase
{

	public function setUp()
	{
		if (!extension_loaded('phalcon')) {
			$this->markTestSkipped('phalcon extension is not loaded.');
		}
	}

	public function testImplementsStorageInterface()
	{
		$session = $this->getMock('\\Phalcon\\Session\\AdapterInterface');

		$storage = new PhalconSessionStorage('storageName', $session);
		$this->assertInstanceOf('\Volcanus\SynchronizerToken\Storage\StorageInterface', $storage);
	}

	public function testGetAttributesReturnFromAdapter()
	{
		$session = $this->getMock('\\Phalcon\\Session\\AdapterInterface');
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
		$session = $this->getMock('\\Phalcon\\Session\\AdapterInterface');
		$session->expects($this->once())
			->method('has')
			->will($this->returnValue(false));

		$storage = new PhalconSessionStorage('storageName', $session);
		$this->assertEquals(array(), $storage->getAttributes());
	}

	public function testSaveAttributesSetToAdapter()
	{
		$attributes = array('foo', 'bar', 'baz');

		$session = $this->getMock('\\Phalcon\\Session\\AdapterInterface');
		$session->expects($this->once())
			->method('set')
			->with($this->equalTo('storageName'), $this->equalTo($attributes));

		$storage = new PhalconSessionStorage('storageName', $session);
		$storage->save($attributes);
	}

}
