<?php
/**
 * @author LiShun
 * Date: 2023/04/28
 */

namespace Lishun\Enums\Demo\ErrorCodeEnums;

use Lishun\Enums\Annotations\EnumCode;
use Lishun\Enums\Annotations\EnumCodePrefix;
use Lishun\Enums\Interfaces\EnumCodeInterface;
use Lishun\Enums\Traits\EnumCodeGet;
#[EnumCodePrefix(10, '系统错误码')]
enum DemoCodeEnum: int implements EnumCodeInterface
{
    use EnumCodeGet;

    // 错误码: 10500, 错误信息: 系统错误
    #[EnumCode('系统错误')]
    case SYSTEM_ERROR = 500;


    #[EnumCode('系统错误1')]
    case SYSTEM_ERROR1 = 501;

}