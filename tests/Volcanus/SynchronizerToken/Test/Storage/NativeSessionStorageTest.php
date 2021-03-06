<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\SynchronizerToken\Test\Storage;

/**
 * Test for NativeSessionStorage
 *
 * @author k.holy74@gmail.com
 */
class NativeSessionStorageTest extends \PHPUnit\Framework\TestCase
{

	public function setUp()
	{
		$_SESSION = array();
	}

	public function testImplementsStorageInterface()
	{
		$attributes = array('foo', 'bar', 'baz');
		$storage = new \Volcanus\SynchronizerToken\Storage\NativeSessionStorage('storageName', $attributes);
		$this->assertInstanceOf('\Volcanus\SynchronizerToken\Storage\StorageInterface', $storage);
	}

	public function testConstructorWithAttributes()
	{
		$attributes = array('foo', 'bar', 'baz');
		$storage = new \Volcanus\SynchronizerToken\Storage\NativeSessionStorage('storageName', $attributes);
		$this->assertEquals($attributes, $storage->getAttributes());
	}

	public function testConstructorImportAttributesFromSession()
	{
		$_SESSION['storageName'] = array('foo', 'bar', 'baz');
		$storage = new \Volcanus\SynchronizerToken\Storage\NativeSessionStorage('storageName');
		$this->assertEquals($_SESSION['storageName'], $storage->getAttributes());
	}

	public function testSaveToSession()
	{
		$attributes = array('foo', 'bar', 'baz');
		$storage = new \Volcanus\SynchronizerToken\Storage\NativeSessionStorage('storageName', $attributes);
		$storage->save();
		$this->assertEquals($_SESSION['storageName'], $storage->getAttributes());
	}

	public function testSaveToSessionWithAttributes()
	{
		$attributes = array('foo', 'bar', 'baz');
		$storage = new \Volcanus\SynchronizerToken\Storage\NativeSessionStorage('storageName');
		$storage->save($attributes);
		$this->assertEquals($attributes, $storage->getAttributes());
		$this->assertEquals($attributes, $_SESSION['storageName']);
	}

}
