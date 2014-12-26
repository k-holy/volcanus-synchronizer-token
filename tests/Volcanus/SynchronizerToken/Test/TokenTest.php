<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\SynchronizerToken\Test;

use Volcanus\SynchronizerToken\Token;

/**
 * Test for Token
 *
 * @author k.holy74@gmail.com
 */
class TokenTest extends \PHPUnit_Framework_TestCase
{

	public function testGetter()
	{
		$expire = time();
		$token = new Token('tokenName', 'tokenValue', $expire);
		$this->assertEquals('tokenName', $token->getName());
		$this->assertEquals('tokenValue', $token->getValue());
		$this->assertEquals($expire, $token->getExpire());
	}

	public function testExpired()
	{
		$expire = time();
		$token = new Token('tokenName', 'tokenValue', $expire);
		$this->assertTrue($token->expired($expire + 1));
	}

	public function testNotExpired()
	{
		$expire = time();
		$token = new Token('tokenName', 'tokenValue', $expire);
		$this->assertFalse($token->expired($expire - 1));
	}

	public function testValid()
	{
		$expire = time();
		$token = new Token('tokenName', 'tokenValue', $expire);
		$this->assertTrue($token->valid('tokenName', 'tokenValue', $expire - 1));
	}

	public function testNotValidWhenNameDoesNotMatch()
	{
		$expire = time();
		$token = new Token('tokenName', 'tokenValue', $expire);
		$this->assertFalse($token->valid('foo', 'tokenValue', $expire - 1));
	}

	public function testNotValidWhenValueDoesNotMatch()
	{
		$expire = time();
		$token = new Token('tokenName', 'tokenValue', $expire);
		$this->assertFalse($token->valid('tokenName', 'foo', $expire - 1));
	}

	public function testNotValidWhenExpired()
	{
		$expire = time();
		$token = new Token('tokenName', 'tokenValue', $expire);
		$this->assertFalse($token->valid('tokenName', 'tokenValue', $expire + 1));
	}

	public function testClone()
	{
		$token = new Token('tokenName', 'tokenValue', time());
		$test = clone $token;
		$this->assertEquals($token, $test);
		$this->assertNotSame($token, $test);
		$this->assertEquals($token->getName(), $test->getName());
		$this->assertEquals($token->getValue(), $test->getValue());
		$this->assertEquals($token->getExpire(), $test->getExpire());
	}

	public function testSerialize()
	{
		$token = new Token('tokenName', 'tokenValue', time());
		$test = unserialize(serialize($token));
		$this->assertEquals($token, $test);
		$this->assertNotSame($token, $test);
		$this->assertEquals($token->getName(), $test->getName());
		$this->assertEquals($token->getValue(), $test->getValue());
		$this->assertEquals($token->getExpire(), $test->getExpire());
	}

	public function testVarExport()
	{
		$token = new Token('tokenName', 'tokenValue', time());
		eval('$test = ' . var_export($token, true) . ';');
		$this->assertEquals($token, $test);
		$this->assertNotSame($token, $test);
		$this->assertEquals($token->getName(), $test->getName());
		$this->assertEquals($token->getValue(), $test->getValue());
		$this->assertEquals($token->getExpire(), $test->getExpire());
	}

}
