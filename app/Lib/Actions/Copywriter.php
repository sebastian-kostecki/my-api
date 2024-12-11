<?php

namespace App\Lib\Actions;

use App\Lib\Interfaces\ActionInterface;
use App\Lib\Traits\ShouldThread;

class Copywriter extends AbstractChatAction implements ActionInterface
{
    use ShouldThread;

    public const NAME = 'Copywriter';

    public const ICON = 'fa-solid fa-pen-to-square';

    public const SHORTCUT = null;

    public const CONFIG = [
        'temperature' => 0.7,
        'top_p' => 1,
    ];

    protected const INSTRUCTIONS = 'You are an experienced English copywriter with a strong background in programming, server hosting, and DNS management. Your task is to create clear, concise, and engaging content that effectively communicates complex technical concepts to a diverse audience. Ensure that your writing is accurate, informative, and accessible, while maintaining a professional tone. Use your expertise to provide detailed explanations, practical examples, and actionable insights.';
}
