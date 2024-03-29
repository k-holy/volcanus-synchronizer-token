<?php
/**
 * Volcanus libraries for PHP 8.1~
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\SynchronizerToken\Test\Storage;

use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Volcanus\SynchronizerToken\Storage\SymfonySessionStorage;
use Volcanus\SynchronizerToken\Storage\StorageInterface;

/**
 * Test for SymfonySessionStorage
 *
 * @author k.holy74@gmail.com
 */
class SymfonySessionStorageTest extends \PHPUnit\Framework\TestCase
{

	public function testImplementsStorageInterface()
	{
        /** @var $session SessionInterface|MockObject */
		$session = $this->createMock(SessionInterface::class);

		$storage = new SymfonySessionStorage('storageName', $session);
		$this->assertInstanceOf(StorageInterface::class, $storage);
	}

	public function testGetAttributesReturnFromAdapter()
	{
        /** @var $session SessionInterface|MockObject */
		$session = $this->createMock(SessionInterface::class);
		$session->expects($this->once())
			->method('has')
			->with($this->equalTo('storageName'))
			->will($this->returnValue(true));
		$session->expects($this->once())
			->method('get')
			->with($this->equalTo('storageName'))
			->will($this->returnValue(array('foo' => 'bar')));

		$storage = new SymfonySessionStorage('storageName', $session);
		$this->assertEquals(array('foo' => 'bar'), $storage->getAttributes());
	}

	public function testGetAttributesReturnEmptyArrayWhenAdapterNotHasTheAttribute()
	{
        /** @var $session SessionInterface|MockObject */
		$session = $this->createMock(SessionInterface::class);
		$session->expects($this->once())
			->method('has')
			->will($this->returnValue(false));

		$storage = new SymfonySessionStorage('storageName', $session);
		$this->assertEquals([], $storage->getAttributes());
	}

	public function testSaveAttributesSetToAdapter()
	{
		$attributes = array('foo', 'bar', 'baz');

        /** @var $session SessionInterface|MockObject */
		$session = $this->createMock(SessionInterface::class);
		$session->expects($this->once())
			->method('set')
			->with($this->equalTo('storageName'), $this->equalTo($attributes));

		$storage = new SymfonySessionStorage('storageName', $session);
		$storage->save($attributes);
	}

}
