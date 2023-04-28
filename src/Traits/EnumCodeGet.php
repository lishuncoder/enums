<?php
/**
 * @author LiShun
 * Date: 2023/04/28
 */

namespace Lishun\Enums\Traits;

use Lishun\Enums\Annotations\EnumCase;
use Lishun\Enums\Annotations\EnumCode;
use Lishun\Enums\Annotations\EnumCodePrefix;
use Lishun\Enums\Interfaces\EnumCodeInterface;
use Lishun\Enums\Utils\EnumStore;
use ReflectionEnum;
use ReflectionEnumUnitCase;

/**
 * @implements EnumCodeInterface
 */
trait EnumCodeGet
{

    /**
     * @return array{prefixCode:null|int,prefixMsg:null|string}
     */
    static public function getEnumsPrefix(): array
    {
        $res = self::getEnumClassAttitude();
        return [
            'prefixCode' => $res->prefixCode ?? null,
            'prefixMsg'  => $res->prefixMsg ?? null,
        ];
    }

    static protected function getEnumClassAttitude(): ?EnumCodePrefix
    {
        return (new ReflectionEnum(static::class))->getAttributes(EnumCodePrefix::class)[0]->newInstance() ?? null;
    }

    protected function getEnumCase(): ?EnumCode
    {
        return (new ReflectionEnumUnitCase($this, $this->name))->getAttributes(EnumCode::class)[0]->newInstance() ?? null;
    }


    /**
     * @param string $name
     * @param array $arguments
     *
     * @return mixed
     * @author LiShun
     * Date: 2023/04/28
     *
     */
    public function __call(string $name, array $arguments)
    {
        $ext = $this->getExt();
        $pos = stripos($name, 'get');
        if ($pos === 0) {
            $getKey = substr($name, 3);
            if ($getKey) {
                $getKey = strtolower(substr($getKey, 0, 1)) . substr($getKey, 1);
                if (isset($ext[$getKey])) {
                    return $ext[$getKey];
                }
            }
        }
        if (isset($ext[$name])) {
            return $ext[$name];
        }

        return null;
    }


    /**
     * 获取错误信息
     *
     * @return null|string
     * @author LiShun
     * Date: 2023/04/28
     */
    public function getMsg(): ?string
    {
        return self::getEnums()[$this->name]['msg'] ?? null;
    }

    /**
     * 获取错误码
     *
     * @return int|null
     * @author LiShun
     * Date: 2023/04/28
     */
    public function getCode(): ?int
    {
        return self::getEnums()[$this->name]['code'] ?? null;
    }

    /**
     * 获取错误码前缀注释
     *
     * @return string|null
     * @author LiShun
     * Date: 2023/04/28
     */
    public function getPrefixMsg(): ?string
    {
        return self::getEnums()[$this->name]['pre']['prefixMsg'] ?? null;
    }


    /**
     * 将枚举转换为数组
     *
     * @return array
     * @author LiShun
     * Date:2023/04/28
     */
    public function toArray(): array
    {
        return [
            'name'  => $this->name,
            'value' => $this->value ?? null,
            'msg'   => $this->getMsg(),
            'code'  => $this->getCode(),
            'ext'   => $this->getExt(),
            'pre'   => [
                'prefixCode' => $this->getPrefixCode() ?? null,
                'prefixMsg'  => $this->getPrefixMsg() ?? null,
            ]
        ];
    }

    /**
     * 获取拓展
     *
     * @param $key
     *
     * @return array|mixed|null
     * @author LiShun
     * Date: 2023/04/28
     *
     */
    public function getExt($key = null): mixed
    {
        if ($key !== null) {
            return self::getEnums()[$this->name]['ext'][$key] ?? null;
        }

        return self::getEnums()[$this->name]['ext'] ?? null;
    }

    /**
     * 获取错误码前缀
     *
     * @return int|null
     * @author LiShun
     * Date: 2023/04/28
     */
    public function getPrefixCode(): ?int
    {
        return self::getEnums()[$this->name]['pre']['prefixCode'] ?? null;
    }


    public static function getEnums(): array
    {
        $enum = new ReflectionEnum(static::class);
        if (EnumStore::isset($enum->getName())) {
            return EnumStore::get($enum->getName());
        }
        $enumCases = $enum->getCases();
        $classObj  = self::getEnumClassAttitude();
        foreach ($enumCases as $enumCase) {
            /** @var self $case */
            $case = $enumCase->getValue();
            $obj  = $case->getEnumCase();

            $caseArr = [
                'name'  => $case->name,
                'value' => $case->value,
                'msg'   => $obj->msg ?? null,
                'code'  => (int)((string)($classObj->prefixCode ?? '') . (string)($case->value ?? '')),
                'ext'   => $obj->ext ?? null,
                'pre'   => [
                    'prefixCode' => $classObj->prefixCode ?? null,
                    'prefixMsg'  => $classObj->prefixMsg ?? null
                ]
            ];

            EnumStore::set($enum->getName(), $case->name, $caseArr);
        }

        return EnumStore::get($enum->getName());
    }


}