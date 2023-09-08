<?php

namespace App\Lib\Interfaces;

interface ActionInterface
{
    public function execute(): string;

    public function setPrompt(string $prompt): void;
}
