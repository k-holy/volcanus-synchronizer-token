<?php
/**
 * Volcanus libraries for PHP 8.1~
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\SynchronizerToken\Test;

use PHPUnit\Framework\MockObject\MockObject;
use Volcanus\SynchronizerToken\Storage\StorageInterface;
use Volcanus\SynchronizerToken\TokenProcessor;
use Volcanus\SynchronizerToken\TokenInterface;
use Volcanus\SynchronizerToken\Test\Token as TestToken;

/**
 * Test for TokenProcessor
 *
 * @author k.holy74@gmail.com
 */
class TokenProcessorTest extends \PHPUnit\Framework\TestCase
{

	public function testTokenNameOnGenerate()
	{
        /** @var $storage StorageInterface|MockObject */
		$storage = $this->createMock(StorageInterface::class);
		$storage->expects($this->once())
			->method('getAttributes')
			->will($this->returnValue([]));
		$storage->expects($this->once())
			->method('save')
			->will($this->returnValue($storage));

		$processor = new TokenProcessor($storage, array(
			'tokenName' => 'TEST_TOKEN',
		));

		$token = $processor->generate();
		$this->assertEquals('TEST_TOKEN', $token->getName());
	}

	public function testTokenNameOnGenerateWithSuffix()
	{
        /** @var $storage StorageInterface|MockObject */
		$storage = $this->createMock(StorageInterface::class);
		$storage->expects($this->once())
			->method('getAttributes')
			->will($this->returnValue([]));
		$storage->expects($this->once())
			->method('save')
			->will($this->returnValue($storage));

		$processor = new TokenProcessor($storage, array(
			'tokenName' => 'TEST_TOKEN',
		));

		$token = $processor->generate(null, '_SUFFIX');

		$this->assertEquals('TEST_TOKEN_SUFFIX', $token->getName());
	}

	public function testLifetimeOnGenerate()
	{
        /** @var $storage StorageInterface|MockObject */
		$storage = $this->createMock(StorageInterface::class);
		$storage->expects($this->once())
			->method('getAttributes')
			->will($this->returnValue([]));
		$storage->expects($this->any())
			->method('save')
			->will($this->returnValue($storage));

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
        /** @var $storage StorageInterface|MockObject */
        $storage = $this->createMock(StorageInterface::class);
		$storage->expects($this->once())
			->method('getAttributes')
			->will($this->returnValue([]));
		$storage->expects($this->any())
			->method('save')
			->will($this->returnValue($storage));

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

    public function testTokenClassOnGenerate()
    {
        /** @var $storage StorageInterface|MockObject */
        $storage = $this->createMock(StorageInterface::class);
        $storage->expects($this->once())
            ->method('getAttributes')
            ->will($this->returnValue([]));
        $storage->expects($this->once())
            ->method('save')
            ->will($this->returnValue($storage));

        $processor = new TokenProcessor($storage, array(
            'tokenClass' => TestToken::class,
        ));

        $token = $processor->generate();
        $this->assertInstanceof(TestToken::class, $token);
        $this->assertInstanceof(TokenInterface::class, $token);
    }

	public function testGeneratorOnGenerate()
	{
        /** @var $storage StorageInterface|MockObject */
        $storage = $this->createMock(StorageInterface::class);
		$storage->expects($this->once())
			->method('getAttributes')
			->will($this->returnValue([]));
		$storage->expects($this->any())
			->method('save')
			->will($this->returnValue($storage));

		$processor = new TokenProcessor($storage, array(
			'generator' => function() {
				return 'TEST';
			},
		));

		$token = $processor->generate();

		$this->assertEquals('TEST', $token->getValue());
	}

	public function testCheck()
	{
        /** @var $storage StorageInterface|MockObject */
        $storage = $this->createMock(StorageInterface::class);
		$storage->expects($this->once())
			->method('getAttributes')
			->will($this->returnValue([]));
		$storage->expects($this->any())
			->method('save')
			->will($this->returnValue($storage));

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
        /** @var $storage StorageInterface|MockObject */
        $storage = $this->createMock(StorageInterface::class);
		$storage->expects($this->once())
			->method('getAttributes')
			->will($this->returnValue([]));
		$storage->expects($this->any())
			->method('save')
			->will($this->returnValue($storage));

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
        /** @var $storage StorageInterface|MockObject */
        $storage = $this->createMock(StorageInterface::class);
		$storage->expects($this->once())
			->method('getAttributes')
			->will($this->returnValue([]));
		$storage->expects($this->any())
			->method('save')
			->will($this->returnValue($storage));

		$generatedTime = time();
		$processor = new TokenProcessor($storage, array(
			'tokenName' => 'TEST_TOKEN',
			'lifetime' => 1,
		));

		$token = $processor->generate(null, '_SUFFIX');

		$this->assertFalse($processor->check($token->getValue(), $generatedTime));
		$this->assertTrue($processor->check($token->getValue(), $generatedTime, '_SUFFIX'));
	}

    public function testCheckAcceptNull()
    {
        /** @var $storage StorageInterface|MockObject */
        $storage = $this->createMock(StorageInterface::class);
        $processor = new TokenProcessor($storage);

        $this->assertFalse($processor->check(null));
    }

}
