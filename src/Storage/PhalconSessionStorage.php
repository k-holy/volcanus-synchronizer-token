<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\SynchronizerToken\Storage;

/**
 * Phalconセッションストレージ
 *
 * @author k-holy <k.holy74@gmail.com>
 */
class PhalconSessionStorage implements \Volcanus\SynchronizerToken\Storage\StorageInterface
{

    /**
     * @var string ストレージ名
     */
    private $name;

    /**
     * @var \Phalcon\Session\AdapterInterface セッションアダプタ
     */
    private $session;

    /**
     * コンストラクタ
     *
     * @param string $name ストレージ名
     * @param \Phalcon\Session\AdapterInterface $session セッションアダプタ
     */
    public function __construct($name, \Phalcon\Session\AdapterInterface $session)
    {
        $this->name = $name;
        $this->session = $session;
    }

    /**
     * 属性値を返します。
     *
     * @return array 属性値
     */
    public function getAttributes()
    {
        if ($this->session->has($this->name)) {
            return $this->session->get($this->name);
        }
        return array();
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
            $this->session->set($this->name, $attributes);
        }
        return $this;
    }

}
