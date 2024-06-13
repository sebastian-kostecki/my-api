<?php

namespace App\Lib\Interfaces;

use App\Models\Assistant;
use App\Models\Thread;

interface ActionInterface
{
    /**
     * @param Assistant $assistant
     * @param Thread $thread
     * @param string $input
     */
    public function __construct(Assistant $assistant, Thread $thread, string $input);

    /**
     * @return string
     */
    public static function getName(): string;

    /**
     * @return string
     */
    public static function getIcon(): string;

    /**
     * @return string|null
     */
    public static function getShortcut(): ?string;

    /**
     * @return array
     */
    public static function getConfig(): ?array;

    /**
     * @return bool
     */
    public function isRequireThread(): bool;

    /**
     * @return mixed
     */
    public function execute(): string;
}
