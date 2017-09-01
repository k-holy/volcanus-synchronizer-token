<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\SynchronizerToken\Test\Storage;

/**
 * Test for SymfonySessionStorage
 *
 * @author k.holy74@gmail.com
 */
class SymfonySessionStorageTest extends \PHPUnit\Framework\TestCase
{

	public function testImplementsStorageInterface()
	{
        /** @var $session \Symfony\Component\HttpFoundation\Session\SessionInterface|\PHPUnit_Framework_MockObject_MockObject */
		$session = $this->createMock('\Symfony\Component\HttpFoundation\Session\SessionInterface');

		$storage = new \Volcanus\SynchronizerToken\Storage\SymfonySessionStorage('storageName', $session);
		$this->assertInstanceOf('\Volcanus\SynchronizerToken\Storage\StorageInterface', $storage);
	}

	public function testGetAttributesReturnFromAdapter()
	{
        /** @var $session \Symfony\Component\HttpFoundation\Session\SessionInterface|\PHPUnit_Framework_MockObject_MockObject */
		$session = $this->createMock('\Symfony\Component\HttpFoundation\Session\SessionInterface');
		$session->expects($this->once())
			->method('has')
			->with($this->equalTo('storageName'))
			->will($this->returnValue(true));
		$session->expects($this->once())
			->method('get')
			->with($this->equalTo('storageName'))
			->will($this->returnValue(array('foo' => 'bar')));

		$storage = new \Volcanus\SynchronizerToken\Storage\SymfonySessionStorage('storageName', $session);
		$this->assertEquals(array('foo' => 'bar'), $storage->getAttributes());
	}

	public function testGetAttributesReturnEmptyArrayWhenAdapterNotHasTheAttribute()
	{
        /** @var $session \Symfony\Component\HttpFoundation\Session\SessionInterface|\PHPUnit_Framework_MockObject_MockObject */
		$session = $this->createMock('\Symfony\Component\HttpFoundation\Session\SessionInterface');
		$session->expects($this->once())
			->method('has')
			->will($this->returnValue(false));

		$storage = new \Volcanus\SynchronizerToken\Storage\SymfonySessionStorage('storageName', $session);
		$this->assertEquals(array(), $storage->getAttributes());
	}

	public function testSaveAttributesSetToAdapter()
	{
		$attributes = array('foo', 'bar', 'baz');

        /** @var $session \Symfony\Component\HttpFoundation\Session\SessionInterface|\PHPUnit_Framework_MockObject_MockObject */
		$session = $this->createMock('\Symfony\Component\HttpFoundation\Session\SessionInterface');
		$session->expects($this->once())
			->method('set')
			->with($this->equalTo('storageName'), $this->equalTo($attributes));

		$storage = new \Volcanus\SynchronizerToken\Storage\SymfonySessionStorage('storageName', $session);
		$storage->save($attributes);
	}

}
