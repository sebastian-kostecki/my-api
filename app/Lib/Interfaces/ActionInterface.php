<?php

namespace App\Lib\Interfaces;

interface ActionInterface
{
    public function execute(): string;

    public function setMessage(string $prompt): void;
}
