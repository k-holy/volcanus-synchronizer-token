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
class TokenTest extends \PHPUnit\Framework\TestCase
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

    /**
     * @throws \Exception
     */
    public function testExpiredByDateTime()
	{
		$expire = new \DateTime(sprintf('@%d', time()));
		$expired = clone $expire;
		$expired->add(new \DateInterval('PT1S'));
		$token = new Token('tokenName', 'tokenValue', $expire);
		$this->assertTrue($token->expired($expired));
	}

	public function testEquals()
	{
		$token = new Token('tokenName', 'tokenValue');
		$this->assertTrue($token->equals('tokenName', 'tokenValue'));
	}

	public function testNotEqualsWhenNameDoesNotMatch()
	{
		$token = new Token('tokenName', 'tokenValue');
		$this->assertFalse($token->equals('tokenNameIsNotEquals', 'tokenValue'));
	}

	public function testNotEqualsWhenValueDoesNotMatch()
	{
		$token = new Token('tokenName', 'tokenValue');
		$this->assertFalse($token->equals('tokenName', 'tokenValueIsNotEquals'));
	}

	public function testValid()
	{
		$token = new Token('tokenName', 'tokenValue');
		$this->assertTrue($token->valid('tokenName', 'tokenValue'));
	}

	public function testNotValidWhenNameDoesNotMatch()
	{
		$token = new Token('tokenName', 'tokenValue');
		$this->assertFalse($token->valid('foo', 'tokenValue'));
	}

	public function testNotValidWhenValueDoesNotMatch()
	{
		$token = new Token('tokenName', 'tokenValue');
		$this->assertFalse($token->valid('tokenName', 'foo'));
	}

	public function testValidWithExpire()
	{
		$expire = time();
		$token = new Token('tokenName', 'tokenValue', $expire);
		$this->assertTrue($token->valid('tokenName', 'tokenValue', $expire - 1));
	}

	public function testNotValidWhenExpired()
	{
		$expire = time();
		$token = new Token('tokenName', 'tokenValue', $expire);
		$this->assertFalse($token->valid('tokenName', 'tokenValue', $expire + 1));
	}

    /**
     * @throws \Exception
     */
    public function testValidWithExpireByDateTime()
	{
		$expire = new \DateTime(sprintf('@%d', time()));
		$token = new Token('tokenName', 'tokenValue', $expire);
		$not_expired = clone $expire;
		$not_expired->sub(new \DateInterval('PT1S'));
		$this->assertTrue($token->valid('tokenName', 'tokenValue', $not_expired));
	}

    /**
     * @throws \Exception
     */
    public function testNotValidWithExpireByDateTime()
	{
		$expire = new \DateTime(sprintf('@%d', time()));
		$token = new Token('tokenName', 'tokenValue', $expire);
		$expired = clone $expire;
		$expired->add(new \DateInterval('PT1S'));
		$this->assertFalse($token->valid('tokenName', 'tokenValue', $expired));
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
        /** @noinspection PhpUndefinedVariableInspection */
		$this->assertEquals($token, $test);
		$this->assertNotSame($token, $test);
		$this->assertEquals($token->getName(), $test->getName());
		$this->assertEquals($token->getValue(), $test->getValue());
		$this->assertEquals($token->getExpire(), $test->getExpire());
	}

}
