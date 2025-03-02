<?php

namespace App\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class ActionNameAttribute
{
    public function __construct(
        public readonly string $name,
    ) {}
}
