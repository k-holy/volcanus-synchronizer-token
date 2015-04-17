<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\SynchronizerToken\Storage;

/**
 * トークンストレージインタフェース
 *
 * @author k-holy <k.holy74@gmail.com>
 */
interface StorageInterface
{

	/**
	 * 属性値を返します。
	 *
	 * @return array 属性値
	 */
	public function getAttributes();

	/**
	 * 属性値を保存します。
	 *
	 * @param array 属性値
	 * @return $this
	 */
	public function save(array $attributes = array());

}
