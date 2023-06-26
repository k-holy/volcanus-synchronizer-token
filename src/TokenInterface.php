<?php
/**
 * Volcanus libraries for PHP 8.1~
 *
 * @copyright k-holy <k.holy74@gmail.com>
 * @license The MIT License (MIT)
 */

namespace Volcanus\SynchronizerToken;

/**
 * トークンインタフェース
 *
 * @author k-holy <k.holy74@gmail.com>
 */
interface TokenInterface
{

    /**
     * コンストラクタ
     *
     * @param string $name トークンの名前
     * @param string $value トークンの値
     * @param \DateTimeInterface|int|null $expire 有効期限のタイムスタンプ or DateTime
     */
    public function __construct(string $name, string $value, \DateTimeInterface|int $expire = null);

    /**
     * トークンの名前を返します。
     *
     * @return string トークンの名前
     */
    public function getName(): string;

    /**
     * トークンの値を返します。
     *
     * @return string トークンの値
     */
    public function getValue(): string;

    /**
     * 有効期限を返します。
     *
     * @return int|null 有効期限のタイムスタンプ
     */
    public function getExpire(): ?int;

    /**
     * 指定されたタイムスタンプでトークンの有効期限が切れているかどうかを返します。
     *
     * @param \DateTimeInterface|int $time 検証するタイムスタンプ or DateTime
     * @return bool 有効期限が切れている場合はTRUE
     */
    public function expired(\DateTimeInterface|int $time): bool;

    /**
     * 指定されたトークン名と値がこのトークンと一致しているかどうかを返します。
     *
     * @param string $name 検証するトークン名
     * @param string $value 検証するトークン値
     * @return bool 値が一致している場合はTRUE
     */
    public function equals(string $name, string $value): bool;

    /**
     * 指定されたトークン名 + トークン値 + タイムスタンプでトークンが有効かどうかを返します。
     *
     * @param string $name 検証するトークン名
     * @param string $value 検証するトークン値
     * @param \DateTimeInterface|int|null $time 検証するタイムスタンプ or DateTime
     * @return bool 有効な場合はTRUE
     */
    public function valid(string $name, string $value, \DateTimeInterface|int $time = null): bool;

}
