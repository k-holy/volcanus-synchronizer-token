<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\SynchronizerToken;

/**
 * トークン
 *
 * @author k-holy <k.holy74@gmail.com>
 */
class Token
{

	/**
	 * @var string トークンの名前
	 */
	private $name;

	/**
	 * @var string トークンの値
	 */
	private $value;

	/**
	 * @var int 有効期限のタイムスタンプ
	 */
	private $expire;

	/**
	 * コンストラクタ
	 *
	 * @param string トークンの名前
	 * @param string トークンの値
	 * @param int 有効期限のタイムスタンプ
	 */
	public function __construct($name, $value, $expire = null)
	{
		$this->name = $name;
		$this->value = $value;
		$this->expire = $expire;
	}

	/**
	 * トークンの名前を返します。
	 *
	 * @return string トークンの名前
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * トークンの値を返します。
	 *
	 * @return string トークンの値
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * 有効期限を返します。
	 *
	 * @return int 有効期限のタイムスタンプ
	 */
	public function getExpire()
	{
		return $this->expire;
	}

	/**
	 * 指定されたトークン名 + トークン値 + タイムスタンプでトークンが有効かどうかを返します。
	 *
	 * @param string 検証するトークン名
	 * @param string 検証するトークン値
	 * @param int 検証するタイムスタンプ
	 * @return bool 有効な場合はTRUE
	 */
	public function valid($name, $value, $time)
	{
		return ($this->equals($name, $value) && !$this->expired($time));
	}

	/**
	 * 指定されたトークン名と値がこのトークンと一致しているかどうかを返します。
	 *
	 * @param string 検証するトークン名
	 * @param string 検証するトークン値
	 * @return bool 値が一致している場合はTRUE
	 */
	public function equals($name, $value)
	{
		return ($this->getName() === $name && $this->getValue() === $value);
	}

	/**
	 * 指定されたタイムスタンプでトークンの有効期限が切れているかどうかを返します。
	 *
	 * @param int 検証するタイムスタンプ
	 * @return bool 有効期限が切れている場合はTRUE
	 */
	public function expired($time)
	{
		return ($this->expire !== null && $this->expire < $time);
	}

	/**
	 * __clone for clone
	 */
	public function __clone()
	{
		foreach (get_object_vars($this) as $name => $value) {
			if (is_object($value)) {
				$this->{$name} = clone $value;
			}
		}
	}

	/**
	 * for serialize()
	 *
	 * @return array
	 */
	public function __sleep()
	{
		return array_keys(get_object_vars($this));
	}

	/**
	 * for var_export()
	 *
	 * @param array
	 * @return object
	 */
	public static function __set_state($args)
	{
		return new static($args['name'], $args['value'], $args['expire']);
	}

}
