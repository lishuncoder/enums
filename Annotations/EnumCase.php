<?php
/**
 * @author LiShun
 * Date: 2023/04/28
 */

namespace Lishun\Enums\Annotations;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
class EnumCase
{

    public function __construct(
        public readonly ?string               $msg = null,
        public readonly mixed                 $data = null,
        public readonly null|string|int|array $group = null,
        public readonly ?array                $ext = null,
    )
    {
    }
}
