<?php
/**
 * Volcanus libraries for PHP
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
class NativeSessionStorage implements \Volcanus\SynchronizerToken\Storage\StorageInterface
{

    /**
     * @var string ストレージ名
     */
    private $name;

    /**
     * @var array 属性値
     */
    private $attributes;

    /**
     * コンストラクタ
     *
     * @param string $name ストレージ名
     * @param array $attributes 属性値
     */
    public function __construct($name, array $attributes = array())
    {
        $this->name = $name;
        $this->attributes = array();
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
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * 属性値を保存します。
     *
     * @param array $attributes 属性値
     * @return $this
     */
    public function save(array $attributes = array())
    {
        if (!empty($attributes)) {
            $this->attributes = $attributes;
        }
        $_SESSION[$this->name] = $this->attributes;
        return $this;
    }

}
