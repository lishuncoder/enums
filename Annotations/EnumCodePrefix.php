<?php
/**
 * @author LiShun
 * Date: 2023/04/28
 */

namespace Lishun\Enums\Annotations;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class EnumCodePrefix
{

    public function __construct(
        public readonly int|string|null $prefixCode = null,
        public readonly ?string         $prefixMsg = null
    )
    {
    }

}