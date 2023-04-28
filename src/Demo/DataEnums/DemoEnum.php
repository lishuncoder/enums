<?php

namespace Lishun\Enums\Demo\DataEnums;

use Lishun\Enums\Annotations\EnumCase;
use Lishun\Enums\Interfaces\EnumCaseInterface;
use Lishun\Enums\Traits\EnumCaseGet;

enum DemoEnum implements EnumCaseInterface
{
    use EnumCaseGet;

    #[EnumCase(msg: '系统错误', data: 1, group: 'sys', ext: [1, 2, 3])]
    case SYSTEM_ERROR;

    #[EnumCase(msg: '系统错误2', data: 2, group: ['sys', 'test'])]
    case SYSTEM_ERROR2;

    #[EnumCase(msg: '系统错误3', data: 2, group: 'test')]
    case SYSTEM_ERROR3;
}
