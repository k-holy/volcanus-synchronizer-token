<?php
/**
 * Volcanus libraries for PHP 8.1~
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
    private string $name;

    /**
     * @var string トークンの値
     */
    private string $value;

    /**
     * @var int|null 有効期限のタイムスタンプ
     */
    private ?int $expire;

    /**
     * コンストラクタ
     *
     * @param string $name トークンの名前
     * @param string $value トークンの値
     * @param \DateTimeInterface|int|null $expire 有効期限のタイムスタンプ or DateTime
     */
    public function __construct(string $name, string $value, \DateTimeInterface|int $expire = null)
    {
        if ($expire instanceof \DateTimeInterface) {
            $expire = $expire->getTimestamp();
        }
        $this->name = $name;
        $this->value = $value;
        $this->expire = $expire;
    }

    /**
     * トークンの名前を返します。
     *
     * @return string トークンの名前
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * トークンの値を返します。
     *
     * @return string トークンの値
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * 有効期限を返します。
     *
     * @return int|null 有効期限のタイムスタンプ
     */
    public function getExpire(): ?int
    {
        return $this->expire;
    }

    /**
     * 指定されたタイムスタンプでトークンの有効期限が切れているかどうかを返します。
     *
     * @param \DateTimeInterface|int $time 検証するタイムスタンプ or DateTime
     * @return bool 有効期限が切れている場合はTRUE
     */
    public function expired(\DateTimeInterface|int $time): bool
    {
        if ($time instanceof \DateTimeInterface) {
            $time = $time->getTimestamp();
        }
        return ($this->expire !== null && $this->expire < $time);
    }

    /**
     * 指定されたトークン名と値がこのトークンと一致しているかどうかを返します。
     *
     * @param string $name 検証するトークン名
     * @param string $value 検証するトークン値
     * @return bool 値が一致している場合はTRUE
     */
    public function equals(string $name, string $value): bool
    {
        return ($this->getName() === $name && $this->getValue() === $value);
    }

    /**
     * 指定されたトークン名 + トークン値 + タイムスタンプでトークンが有効かどうかを返します。
     *
     * @param string $name 検証するトークン名
     * @param string $value 検証するトークン値
     * @param \DateTimeInterface|int|null $time 検証するタイムスタンプ or DateTime
     * @return bool 有効な場合はTRUE
     */
    public function valid(string $name, string $value, \DateTimeInterface|int $time = null): bool
    {
        return ($this->equals($name, $value) && ($time === null || !$this->expired($time)));
    }

    /**
     * for var_export()
     *
     * @param array $args
     * @return self
     */
    public static function __set_state(array $args)
    {
        return new static($args['name'], $args['value'], $args['expire']);
    }

}
