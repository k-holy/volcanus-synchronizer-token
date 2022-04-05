<?php
/**
 * Volcanus libraries for PHP
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\SynchronizerToken\Storage;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Symfonyセッションストレージ
 *
 * @author k-holy <k.holy74@gmail.com>
 */
class SymfonySessionStorage implements StorageInterface
{

    /**
     * @var string ストレージ名
     */
    private $name;

    /**
     * @var SessionInterface セッションアダプタ
     */
    private $session;

    /**
     * コンストラクタ
     *
     * @param string $name ストレージ名
     * @param SessionInterface $session セッションアダプタ
     */
    public function __construct(string $name, SessionInterface $session)
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
