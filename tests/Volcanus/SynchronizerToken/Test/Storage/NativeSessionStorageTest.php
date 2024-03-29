<?php
/**
 * Volcanus libraries for PHP 8.1~
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\SynchronizerToken\Test\Storage;

use Volcanus\SynchronizerToken\Storage\NativeSessionStorage;
use Volcanus\SynchronizerToken\Storage\StorageInterface;

/**
 * Test for NativeSessionStorage
 *
 * @author k.holy74@gmail.com
 */
class NativeSessionStorageTest extends \PHPUnit\Framework\TestCase
{

    public function setUp(): void
    {
        $_SESSION = [];
    }

    public function testImplementsStorageInterface()
    {
        $attributes = array('foo', 'bar', 'baz');
        $storage = new NativeSessionStorage('storageName', $attributes);
        $this->assertInstanceOf(StorageInterface::class, $storage);
    }

    public function testConstructorWithAttributes()
    {
        $attributes = array('foo', 'bar', 'baz');
        $storage = new NativeSessionStorage('storageName', $attributes);
        $this->assertEquals($attributes, $storage->getAttributes());
    }

    public function testConstructorImportAttributesFromSession()
    {
        $_SESSION['storageName'] = array('foo', 'bar', 'baz');
        $storage = new NativeSessionStorage('storageName');
        $this->assertEquals($_SESSION['storageName'], $storage->getAttributes());
    }

    public function testSaveToSession()
    {
        $attributes = array('foo', 'bar', 'baz');
        $storage = new NativeSessionStorage('storageName', $attributes);
        $storage->save();
        $this->assertEquals($_SESSION['storageName'], $storage->getAttributes());
    }

    public function testSaveToSessionWithAttributes()
    {
        $attributes = array('foo', 'bar', 'baz');
        $storage = new NativeSessionStorage('storageName');
        $storage->save($attributes);
        $this->assertEquals($attributes, $storage->getAttributes());
        $this->assertEquals($attributes, $_SESSION['storageName']);
    }

}
