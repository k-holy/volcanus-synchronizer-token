<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\SynchronizerToken\Storage;

use Phalcon\Session\ManagerInterface;

/**
 * Phalcon4セッションストレージ
 *
 * @author k-holy <k.holy74@gmail.com>
 */
class Phalcon4SessionStorage implements StorageInterface
{

    /**
     * @var string ストレージ名
     */
    private $name;

    /**
     * @var ManagerInterface セッションマネージャ
     */
    private $session;

    /**
     * コンストラクタ
     *
     * @param string $name ストレージ名
     * @param ManagerInterface $session セッションマネージャ
     */
    public function __construct(string $name, ManagerInterface $session)
    {
        $this->name = $name;
        $this->session = $session;
    }

    /**
     * 属性値を返します。
     *
     * @return array 属性値
     */
    public function getAttributes(): array
    {
        if ($this->session->has($this->name)) {
            return $this->session->get($this->name);
        }
        return [];
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
            $this->session->set($this->name, $attributes);
        }
        return $this;
    }

}
