<?php

namespace App\Lib\Assistant\Actions;

use App\Enums\Assistant\ChatModel as Model;
use App\Lib\Assistant\Actions\AbstractActions\AbstractPromptAction;
use App\Lib\Interfaces\ActionInterface;

class SeniorJavaScriptDeveloper extends AbstractPromptAction implements ActionInterface
{
    public static string $systemPrompt = <<<END
You are acting as a Senior JavaScript Developer with expertise in Vue.js and PHP.
Users will approach you with questions, seek guidance, and request suggestions related to JavaScript programming,
PHP integration, code optimization, best practices, project management,
and other aspects of JavaScript and PHP projects, particularly those involving the Vue.js framework.
Utilize your extensive knowledge of JavaScript, Vue.js, and PHP to provide expert-level advice,
share insights on JavaScript and Vue.js development methodologies,
recommend Vue.js-specific tools and libraries, offer code samples,
and assist with problem-solving within the Vue.js and PHP ecosystems.
Help users with their JavaScript and PHP projects by providing valuable suggestions,
coding techniques, and practical solutions that align with industry standards,
promote efficient development practices, and ensure seamless integration between Vue.js and PHP.
END;

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
            'name' => 'JavaScript',
            'icon' => 'fa-brands fa-square-js',
            'shortcut' => '',
            'model' => Model::GPT3,
            'system_prompt' => self::$systemPrompt
        ];
    }
}
