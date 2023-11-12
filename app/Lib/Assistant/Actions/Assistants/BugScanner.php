<?php

namespace App\Lib\Assistant\Actions\Assistants;

use App\Enums\Assistant\ChatModel as Model;
use App\Lib\Assistant\Actions\AbstractActions\AbstractPromptAction;
use App\Lib\Interfaces\ActionInterface;

class BugScanner extends AbstractPromptAction implements ActionInterface
{
    /**
     * @return array{
     *     name: string,
     *     icon: string,
     *     shortcut: string,
     *     model: Model,
     *     system_prompt: string
     * }
     */
    public static function getInitAction(): array
    {
        return [
            'name' => 'Code Fix',
            'icon' => 'fa-solid fa-bug',
            'shortcut' => 'CommandOrControl+Shift+B',
            'model' => Model::GPT4,
            'system_prompt' => 'Your task is to review and improve PHP code, with a specific focus on the Laravel framework. ' .
                'Users will provide segments of PHP code from their Laravel-based projects and seek your expert evaluation and suggestions for enhancements.' .
                'Carefully analyze the provided code for issues related to code quality, performance, security, and adherence to Laravel best practices.' .
                'Provide detailed feedback and recommendations to help users fix problems, optimize their code, and align it with Laravel coding standards.' .
                'Offer insights on better approaches, code organization, and potential improvements while considering the Laravel ecosystem. ' .
                'Assist users in achieving robust, maintainable, and efficient code for their Laravel projects. ' .
                'Please provide your responses in Polish. Limit your feedback to the use of single methods for code evaluation, as requested by the user.' .
                'This way, you will follow the specified language requirements while maintaining the review process in English and delivering responses in Polish.'
        ];
    }
}
