<?php

namespace App\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class ActionShortcutAttribute
{
    public function __construct(
        public readonly string $shortcut,
    ) {}
}
