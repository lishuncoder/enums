<?php
/**
 * @author LiShun
 * Date: 2023/04/28
 */

namespace Lishun\Enums\Traits;

use Lishun\Enums\Annotations\EnumCase;
use Lishun\Enums\Interfaces\EnumCaseInterface;
use Lishun\Enums\Utils\EnumGroups;
use Lishun\Enums\Utils\EnumStore;
use ReflectionEnum;
use ReflectionEnumUnitCase;

trait EnumCaseGet
{

    /**
     * 根据枚举实例的分组返回值对应的枚举实例
     *
     * @param mixed                         $value
     * @param EnumCaseInterface|null|string $case
     *
     * @return EnumCaseInterface|null
     */
    public static function tryFromByCase(mixed $value, string|EnumCaseInterface|null $case = null): ?EnumCaseInterface
    {
        if (!$value) {
            return null;
        }
        $currentArr = [];
        $cases      = self::cases();
        if (!$case) {
            $list = self::getEnums();

            foreach ($list as $v) {
                if (($v['data'] ?? null) === $value) {
                    $currentArr = $value;
                    break;
                }
            }

        } else {
            if ($case instanceof EnumCaseInterface) {
                $list = self::getGroupEnums($case->getGroup());
            } else {
                $list = self::getGroupEnums($case);
            }

            foreach ($list as $v) {
                if (count(array_filter($v, 'is_array')) > 0) {
                    foreach ($v as $item) {
                        if ($value === ($item['data'] ?? null)) {
                            $currentArr = $item;
                            break 2;
                        }
                    }
                } elseif ($value === ($v['data'] ?? null)) {
                    $currentArr = $v;
                    break;
                }

            }
        }

        if (!$currentArr) {
            return null;
        }

        $currenCase = null;
        foreach ($cases as $c) {
            if ($c->name === $currentArr['name']) {
                $currenCase = $c;
                break;
            }
        }
        return $currenCase;
    }

    /**
     * 根据实例返回分组中该值对应msg
     *
     * @param mixed                         $value
     * @param string|EnumCaseInterface|null $case
     *
     * @return string
     */
    public static function tryMsgFromByCase(mixed $value, string|EnumCaseInterface|null $case = null): string
    {
        $currenCase = self::tryFromByCase($value, $case);
        if (!$currenCase) {
            return '';
        }

        return $currenCase->getMsg() ?? '';
    }

    /**
     * 获取枚举解释
     *
     * @return EnumCase|null
     * @author LiShun
     * Date: 2023/04/28
     */
    protected function getEnumCase(): null|EnumCase
    {
        if (null === ($rAttr = (new ReflectionEnumUnitCase($this, $this->name))->getAttributes(EnumCase::class, \ReflectionAttribute::IS_INSTANCEOF)[0] ?? null)) {
            return null;
        }
        return $rAttr->newInstance() ?? null;
    }

    /**
     * @return array
     */
    public static function getEnums(): array
    {
        $enum = new ReflectionEnum(static::class);
        if (EnumStore::isset($enum->getName())) {
            return EnumStore::get($enum->getName());
        }
        $enumCases = $enum->getCases();
        foreach ($enumCases as $enumCase) {
            /** @var self $case */
            $case    = $enumCase->getValue();
            $obj     = $case->getEnumCase();
            $caseArr = [
                'name'  => $case->name,
                'value' => $case->value ?? null,
                'msg'   => $obj->msg ?? null,
                'data'  => $obj->data ?? null,
                'group' => $obj->group ?? null,
                'ext'   => $obj->ext ?? null,
            ];

            EnumStore::set($enum->getName(), $case->name, $caseArr);
        }

        return EnumStore::get($enum->getName());
    }

    /**
     * @param string $name
     * @param array  $arguments
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
                if (isset($ext[ $getKey ])) {
                    return $ext[ $getKey ];
                }
            }
        }
        if (isset($ext[ $name ])) {
            return $ext[ $name ];
        }

        return null;
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
            return self::getEnums()[ $this->name ]['ext'][ $key ] ?? null;
        }

        return self::getEnums()[ $this->name ]['ext'] ?? null;
    }


    /**
     * 将枚举转换为数组
     *
     * @return array
     * @author LiShun
     * Date: 2023/04/28
     */
    public function toArray(): array
    {
        return [
            'name'  => $this->name ?? null,
            'value' => $this->value ?? null,
            'msg'   => $this->getMsg(),
            'data'  => $this->getData(),
            'group' => $this->getGroup(),
            'ext'   => $this->getExt(),
        ];
    }

    /**
     * 解释
     *
     * @return string|null
     * @author LiShun
     * Date: 2023/04/28
     */
    public function getMsg(): ?string
    {
        return self::getEnums()[ $this->name ]['msg'] ?? null;
    }


    /**
     * 分组名
     *
     * @return string|int|null|array
     * @author LiShun
     * Date: 2023/04/28
     */
    public function getGroup(): string|int|null|array
    {
        return self::getEnums()[ $this->name ]['group'] ?? null;
    }


    public function getData(): mixed
    {
        return self::getEnums()[ $this->name ]['data'] ?? null;
    }

    /**
     * 加载分组数据
     *
     * @return array
     * @author LiShun
     * Date: 2023/04/28
     */
    protected static function loadGroupsEnums(): array
    {
        $enum = new ReflectionEnum(static::class);
        if (EnumGroups::issetGroups($enum->getName())) {
            return EnumGroups::getGroups($enum->getName());
        }
        $enumCases = $enum->getCases();
        foreach ($enumCases as $enumCase) {
            /** @var self $case */
            $case    = $enumCase->getValue();
            $obj     = $case->getEnumCase();
            $caseArr = [
                'name'  => $case->name,
                'value' => $case->value ?? null,
                'msg'   => $obj->msg ?? null,
                'data'  => $obj->data ?? null,
                'group' => $obj->group ?? null,
                'ext'   => $obj->ext ?? null,
            ];
            if (is_array($caseArr['group'])) {
                foreach ($caseArr['group'] as $v) {
                    !empty($v) && EnumGroups::setGroups($enum->getName(), $v, $case->name, $caseArr);
                }
            } else {
                EnumGroups::setGroups($enum->getName(), $obj->group ?? '', $case->name, $caseArr);
            }
        }

        return EnumGroups::getGroups($enum->getName());
    }


    /**
     * 获取分组数据
     *
     * @param string|int|array|EnumCaseInterface|null $groupName
     *
     * @return array|null
     * @author LiShun
     * Date: 2023/04/28
     */
    public static function getGroupEnums(string|int|null|array|EnumCaseInterface $groupName = null): array|null
    {
        $groups = self::loadGroupsEnums();

        $res = [];

        if ($groupName instanceof EnumCaseInterface) {
            $groupName = $groupName->getGroup();
        }

        if ($groupName !== null) {
            if (is_array($groupName)) {
                foreach ($groupName as $value) {
                    $value && $res[ $value ] = $groups[ $value ] ?? null;
                }
            } else {
                $res = $groups[ $groupName ] ?? null;
            }
            return $res;
        }

        return $groups;
    }
}