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

	public function testGetName()
	{
		$name = 'tokenName';
		$value = 'tokenValue';
		$expire = time() + 3600;
		$token = new Token($name, $value, $expire);
		$this->assertEquals($name, $token->getName());
	}

}
