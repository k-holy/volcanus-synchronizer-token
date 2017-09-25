<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\SynchronizerToken\Test;

use Volcanus\SynchronizerToken\TokenProcessor;

/**
 * Test for TokenProcessor
 *
 * @author k.holy74@gmail.com
 */
class TokenProcessorTest extends \PHPUnit_Framework_TestCase
{

    public function testTokenNameOnGenerate()
    {
        /** @var $storage \Volcanus\SynchronizerToken\Storage\StorageInterface|\PHPUnit_Framework_MockObject_MockObject */
        $storage = $this->getMock('\Volcanus\SynchronizerToken\Storage\StorageInterface');
        $storage->expects($this->once())
            ->method('getAttributes')
            ->will($this->returnValue(array()));
        $storage->expects($this->once())
            ->method('save')
            ->will($this->returnValue(true));

        $processor = new TokenProcessor($storage, array(
            'tokenName' => 'TEST_TOKEN',
        ));

        $token = $processor->generate();
        $this->assertEquals('TEST_TOKEN', $token->getName());
    }

    public function testTokenNameOnGenerateWithSuffix()
    {
        /** @var $storage \Volcanus\SynchronizerToken\Storage\StorageInterface|\PHPUnit_Framework_MockObject_MockObject */
        $storage = $this->getMock('\Volcanus\SynchronizerToken\Storage\StorageInterface');
        $storage->expects($this->once())
            ->method('getAttributes')
            ->will($this->returnValue(array()));
        $storage->expects($this->once())
            ->method('save')
            ->will($this->returnValue(true));

        $processor = new TokenProcessor($storage, array(
            'tokenName' => 'TEST_TOKEN',
        ));

        $token = $processor->generate(null, '_SUFFIX');

        $this->assertEquals('TEST_TOKEN_SUFFIX', $token->getName());
    }

    public function testLifetimeOnGenerate()
    {
        /** @var $storage \Volcanus\SynchronizerToken\Storage\StorageInterface|\PHPUnit_Framework_MockObject_MockObject */
        $storage = $this->getMock('\Volcanus\SynchronizerToken\Storage\StorageInterface');
        $storage->expects($this->once())
            ->method('getAttributes')
            ->will($this->returnValue(array()));
        $storage->expects($this->any())
            ->method('save')
            ->will($this->returnValue(true));

        $generatedTime = time();
        $processor = new TokenProcessor($storage, array(
            'lifetime' => 1,
        ));

        $token = $processor->generate($generatedTime);

        $this->assertFalse($token->expired($generatedTime));
        $this->assertTrue($token->expired($generatedTime + 2));
    }

    public function testCapacityOnGenerate()
    {
        /** @var $storage \Volcanus\SynchronizerToken\Storage\StorageInterface|\PHPUnit_Framework_MockObject_MockObject */
        $storage = $this->getMock('\Volcanus\SynchronizerToken\Storage\StorageInterface');
        $storage->expects($this->once())
            ->method('getAttributes')
            ->will($this->returnValue(array()));
        $storage->expects($this->any())
            ->method('save')
            ->will($this->returnValue(true));

        $processor = new TokenProcessor($storage, array(
            'capacity' => 1,
        ));

        $this->assertCount(0, $processor->getTokens());
        /** @noinspection PhpUnusedLocalVariableInspection */
        $token = $processor->generate();
        $this->assertCount(1, $processor->getTokens());
        /** @noinspection PhpUnusedLocalVariableInspection */
        $token = $processor->generate();
        $this->assertCount(1, $processor->getTokens());

        $processor->config('capacity', 2);
        /** @noinspection PhpUnusedLocalVariableInspection */
        $token = $processor->generate();
        $this->assertCount(2, $processor->getTokens());
        /** @noinspection PhpUnusedLocalVariableInspection */
        $token = $processor->generate();
        $this->assertCount(2, $processor->getTokens());

    }

    public function testGeneratorOnGenerate()
    {
        /** @var $storage \Volcanus\SynchronizerToken\Storage\StorageInterface|\PHPUnit_Framework_MockObject_MockObject */
        $storage = $this->getMock('\Volcanus\SynchronizerToken\Storage\StorageInterface');
        $storage->expects($this->once())
            ->method('getAttributes')
            ->will($this->returnValue(array()));
        $storage->expects($this->any())
            ->method('save')
            ->will($this->returnValue(true));

        $processor = new TokenProcessor($storage, array(
            'generator' => function () {
                return 'TEST';
            },
        ));

        $token = $processor->generate();

        $this->assertEquals('TEST', $token->getValue());
    }

    public function testCheck()
    {
        /** @var $storage \Volcanus\SynchronizerToken\Storage\StorageInterface|\PHPUnit_Framework_MockObject_MockObject */
        $storage = $this->getMock('\Volcanus\SynchronizerToken\Storage\StorageInterface');
        $storage->expects($this->once())
            ->method('getAttributes')
            ->will($this->returnValue(array()));
        $storage->expects($this->any())
            ->method('save')
            ->will($this->returnValue(true));

        $processor = new TokenProcessor($storage, array(
            'lifetime' => 1,
        ));

        $generatedTime = time();
        $token = $processor->generate($generatedTime);

        $this->assertTrue($processor->check($token->getValue()));
        sleep(2);
        $this->assertFalse($processor->check($token->getValue()));
    }

    public function testCheckWithTime()
    {
        /** @var $storage \Volcanus\SynchronizerToken\Storage\StorageInterface|\PHPUnit_Framework_MockObject_MockObject */
        $storage = $this->getMock('\Volcanus\SynchronizerToken\Storage\StorageInterface');
        $storage->expects($this->once())
            ->method('getAttributes')
            ->will($this->returnValue(array()));
        $storage->expects($this->any())
            ->method('save')
            ->will($this->returnValue(true));

        $generatedTime = time();
        $processor = new TokenProcessor($storage, array(
            'lifetime' => 1,
        ));

        $token = $processor->generate($generatedTime);

        $this->assertTrue($processor->check($token->getValue(), $generatedTime));
        $this->assertFalse($processor->check($token->getValue(), $generatedTime + 2));
    }

    public function testCheckWithSuffix()
    {
        /** @var $storage \Volcanus\SynchronizerToken\Storage\StorageInterface|\PHPUnit_Framework_MockObject_MockObject */
        $storage = $this->getMock('\Volcanus\SynchronizerToken\Storage\StorageInterface');
        $storage->expects($this->once())
            ->method('getAttributes')
            ->will($this->returnValue(array()));
        $storage->expects($this->any())
            ->method('save')
            ->will($this->returnValue(true));

        $generatedTime = time();
        $processor = new TokenProcessor($storage, array(
            'tokenName' => 'TEST_TOKEN',
            'lifetime' => 1,
        ));

        $token = $processor->generate(null, '_SUFFIX');

        $this->assertFalse($processor->check($token->getValue(), $generatedTime));
        $this->assertTrue($processor->check($token->getValue(), $generatedTime, '_SUFFIX'));
    }

}
