<?php

namespace App\Lib\Interfaces;

use App\Enums\Assistant\ChatModel;
use App\Lib\Assistant\Assistant;

interface ActionInterface
{
    /**
     * @param Assistant $assistant
     */
    public function __construct(Assistant $assistant);

    /**
     * @return void
     */
    public function execute(): void;

    /**
     * @return array{
     *     name: string,
     *     icon: string,
     *     shortcut: string,
     *     model: ChatModel
     * }
     */
    public static function getInitAction(): array;
}
