<?php

namespace App\Lib\Interfaces;

use App\Models\Action;
use App\Models\Assistant;
use App\Models\Thread;

interface ActionInterface
{
    public function __construct(Assistant $assistant, Action $action, Thread $thread, string $input);

    public static function getName(): string;

    public static function getIcon(): string;

    public static function getShortcut(): ?string;

    public static function getConfig(): ?array;

    public function isRequireThread(): bool;

    /**
     * @return mixed
     */
    public function execute(): string;
}
