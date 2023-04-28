<?php
/**
 * @author LiShun
 * Date: 2023/04/28
 */

namespace Lishun\Enums\Annotations;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
class EnumCode
{
    public function __construct(
        public readonly ?string $msg = null,
        public readonly ?array  $ext = null,
    )
    {
    }

}