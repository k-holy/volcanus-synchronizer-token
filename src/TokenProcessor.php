<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\SynchronizerToken;

/**
 * トークン処理クラス
 *
 * @author k-holy <k.holy74@gmail.com>
 */
class TokenProcessor
{

	/**
	 * @var array 設定値
	 */
	private $config;

	/**
	 * @var array 発行したトークンのリスト
	 */
	private $tokens;

	/**
	 * @var \Volcanus\SynchronizerToken\Storage\StorageInterface
	 */
	private $storage;

	/**
	 * コンストラクタ
	 *
	 * @param \Volcanus\SynchronizerToken\Storage\StorageInterface $storage ストレージ
	 * @param array $configurations オプション設定
	 */
	public function __construct(\Volcanus\SynchronizerToken\Storage\StorageInterface $storage, array $configurations = array())
	{
		$this->initialize($storage, $configurations);
	}

	/**
	 * インスタンスを初期化して返します。
	 *
	 * @param \Volcanus\SynchronizerToken\Storage\StorageInterface $storage ストレージ
	 * @param array $configurations オプション設定
	 * @return $this
	 */
	public function initialize(\Volcanus\SynchronizerToken\Storage\StorageInterface $storage, array $configurations = array())
	{
		$this->config = array();
		$this->config['tokenName'] = 'csrf_token'; // トークン名
		$this->config['lifetime'] = 1800; // トークン生存期間 (秒)
		$this->config['capacity'] = 10; // トークン保持容量
		$this->config['generator'] = null; // トークン値生成関数
		if (!empty($configurations)) {
			foreach ($configurations as $name => $value) {
				$this->config($name, $value);
			}
		}
		if ($this->config['generator'] === null) {
			$this->config['generator'] = (function_exists('openssl_random_pseudo_bytes'))
				? function () {
					return rtrim(strtr(base64_encode(openssl_random_pseudo_bytes(48)), '+/', '-_'), '='); // 64byte random string
				}
				: function () {
					$chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_.';
					$max = strlen($chars) - 1;
					$random = '';
					for ($i = 0; $i < 64; $i++) {
						$random .= $chars[mt_rand(0, $max)];
					}
					return $random; // 64byte random string
				};
		}
		$this->tokens = $storage->getAttributes();
		$this->storage = $storage;
		return $this;
	}

	/**
	 * 引数1の場合は指定された設定の値を返します。
	 * 引数2の場合は指定された設置の値をセットして$thisを返します。
	 *
	 * @param string $name 設定名
	 * @return mixed 設定値 または $this
	 */
	public function config($name)
	{
		switch (func_num_args()) {
		case 1:
			return $this->config[$name];
		case 2:
			$value = func_get_arg(1);
			if (isset($value)) {
				switch ($name) {
				case 'tokenName':
					if (!is_string($value)) {
						throw new \InvalidArgumentException(
							sprintf('The config parameter "%s" only accepts string.', $name));
					}
					break;
				case 'lifetime':
				case 'capacity':
					if (!is_int($value) && !ctype_digit($value)) {
						throw new \InvalidArgumentException(
							sprintf('The config parameter "%s" only accepts numeric.', $name));
					}
					$value = intval($value);
					break;
				case 'generator':
					if (!is_callable($value)) {
						throw new \InvalidArgumentException(
							sprintf('The config parameter "%s" only accepts callable.', $name));
					}
					break;
				default:
					throw new \InvalidArgumentException(
						sprintf('The config parameter "%s" is not defined.', $name)
					);
				}
				$this->config[$name] = $value;
			}
			return $this;
		}
		throw new \InvalidArgumentException('Invalid argument count.');
	}

	/**
	 * トークン名を生成して返します。
	 *
	 * @param string $suffix トークン名の接尾辞
	 * @return string トークン名
	 */
	public function getTokenName($suffix = null)
	{
		$tokenName = $this->config('tokenName');
		if (isset($suffix)) {
			$tokenName .= $suffix;
		}
		return $tokenName;
	}

	/**
	 * 全てのトークンを返します。
	 *
	 * @return array トークンの配列
	 */
	public function getTokens()
	{
		return $this->tokens;
	}

	/**
	 * トークンを発行して値を返します。
	 *
	 * @param int $time 発行日時のタイムスタンプ
	 * @param string $suffix トークン名の接尾辞
	 * @return \Volcanus\SynchronizerToken\Token トークン
	 */
	public function generate($time = null, $suffix = null)
	{
		if ($time === null) {
			$time = time();
		}
		$tokenName = $this->getTokenName($suffix);
		$lifetime = $this->config('lifetime');
		$capacity = $this->config('capacity');
		$generator = $this->config('generator');
		$token = new \Volcanus\SynchronizerToken\Token(
			$tokenName,
			call_user_func($generator),
			($lifetime !== null) ? $time + $lifetime : null
		);
		$this->tokens[] = $token;
		$count = count($this->tokens);
		if ($count > $capacity) {
			array_splice($this->tokens, 0, $count - $capacity);
		}
		$this->storage->save($this->tokens);
		return $token;
	}

	/**
	 * トークンが有効かどうかを返します。
	 *
	 * @param string $tokenValue トークン値
	 * @param int $time チェック時刻
	 * @param string $suffix トークン名の接尾辞
     * @return boolean
	 */
	public function check($tokenValue, $time = null, $suffix = null)
	{
		if ($time === null) {
			$time = time();
		}
		$tokenName = $this->getTokenName($suffix);
		foreach ($this->tokens as $token) {
			if ($token->valid($tokenName, $tokenValue, $time)) {
				$this->storage->save($this->tokens);
				return true;
			}
		}
		return false;
	}

}
