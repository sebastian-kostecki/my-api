<?php

namespace App\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class ActionIconAttribute
{
    public function __construct(
        public readonly string $icon,
    ) {}
}
