<?php
/**
 * @author LiShun
 * Date: 2023/04/28
 */

namespace Lishun\Enums\Interfaces;


use BackedEnum;

/**
 * @property string $name
 * @property int $value
 * @extends BackedEnum
 */
interface EnumCodeInterface
{
    /**
     * 获取错误信息
     *
     * @return ?string
     * @author LiShun
     * Date: 2023/04/28
     */
    public function getMsg(): ?string;

    /**
     * 获取错误码
     *
     * @return ?int
     * @author LiShun
     * Date: 2023/04/28
     */
    public function getCode(): ?int;


    /**
     * 获取扩展信息
     *
     * @param null $key
     * @return mixed
     * @author LiShun
     * Date: 2023/04/28
     */
    public function getExt($key = null): mixed;
}