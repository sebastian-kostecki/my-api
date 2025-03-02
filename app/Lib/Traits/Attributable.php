<?php

namespace App\Lib\Traits;

use ReflectionClass;

trait Attributable
{
    public static function getAttribute(string $attributeClass): mixed
    {
        $reflection = new ReflectionClass(static::class);
        $attributes = $reflection->getAttributes($attributeClass);

        foreach ($attributes as $attribute) {
            $values = $attribute->getArguments();

            return $values[0];
        }

        return null;
    }
}
