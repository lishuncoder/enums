<?php
/**
 * @author LiShun
 * Date: 2023/04/28
 */

namespace Lishun\Enums\Interfaces;


interface EnumCaseInterface
{
    /**
     * 获取拓展
     *
     * @author LiShun
     * Date: 2023/04/28
     *
     * @param $key
     *
     * @return array|mixed|null
     */
    public function getExt($key = null): mixed;

    /**
     * 获取信息
     *
     * @author LiShun
     * Date: 2023/04/28
     * @return string|null
     */
    public function getMsg(): ?string;

    /**
     * 获取分组名
     *
     * @return string|int|array|null
     * @author LiShun
     * Date: 2023/04/28
     */
    public function getGroup(): string|int|null|array;


    /**
     * 获取数据
     *
     * @return mixed
     * @author LiShun
     * Date: 2023/04/28
     */
    public function getData(): mixed;

    /**
     * 获取分组数据
     *
     * @param string|int|array|null $groupName
     * @return  array|null
     * @author LiShun
     * Date: 2023/04/28
     */
    public static function getGroupEnums(string|int|null|array $groupName = null): array|null;


    /**
     * 获取所有常量数据
     *
     * @return array
     * @author LiShun
     * Date: 2023/04/28
     */
    public static function getEnums(): array;

    /**
     * 将枚举转换为数组
     *
     * @author LiShun
     * Date: 2023/04/28
     * @return array
     */
    public function toArray(): array;


}