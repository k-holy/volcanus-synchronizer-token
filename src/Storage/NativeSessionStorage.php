<?php
/**
 * Volcanus libraries for PHP 8.1~
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\SynchronizerToken\Storage;

/**
 * PHPネイティブセッションストレージ
 *
 * @author k-holy <k.holy74@gmail.com>
 */
class NativeSessionStorage implements StorageInterface
{

    /**
     * @var string ストレージ名
     */
    private string $name;

    /**
     * @var array 属性値
     */
    private array $attributes;

    /**
     * コンストラクタ
     *
     * @param string $name ストレージ名
     * @param array $attributes 属性値
     */
    public function __construct(string $name, array $attributes = [])
    {
        $this->name = $name;
        $this->attributes = [];
        if (!empty($attributes)) {
            $this->attributes = $attributes;
        } elseif (isset($_SESSION[$name])) {
            $this->attributes = $_SESSION[$name];
        }
    }

    /**
     * 属性値を返します。
     *
     * @return array 属性値
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * 属性値を保存します。
     *
     * @param array $attributes 属性値
     * @return StorageInterface
     */
    public function save(array $attributes = []): StorageInterface
    {
        if (!empty($attributes)) {
            $this->attributes = $attributes;
        }
        $_SESSION[$this->name] = $this->attributes;
        return $this;
    }

}
