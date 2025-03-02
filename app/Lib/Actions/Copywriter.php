<?php

namespace App\Lib\Actions;

use App\Attributes\ActionIconAttribute;
use App\Attributes\ActionNameAttribute;
use App\Lib\Actions\AbstractActions\AbstractChatAction;
use App\Lib\Interfaces\ActionInterface;
use App\Lib\Traits\ShouldThread;

#[ActionNameAttribute('Copywriter')]
#[ActionIconAttribute('fa-solid fa-pen-to-square')]
class Copywriter extends AbstractChatAction implements ActionInterface
{
    use ShouldThread;

    public const CONFIG = [
        'temperature' => 0.7,
        'top_p' => 1,
    ];

    protected const INSTRUCTIONS = 'You are an experienced English copywriter with a strong background in programming, server hosting, and DNS management. Your task is to create clear, concise, and engaging content that effectively communicates complex technical concepts to a diverse audience. Ensure that your writing is accurate, informative, and accessible, while maintaining a professional tone. Use your expertise to provide detailed explanations, practical examples, and actionable insights.';
}
