<?php

namespace App\Lib\Actions;

use App\Lib\Interfaces\ActionInterface;
use App\Models\Action;
use App\Models\Assistant;
use App\Models\Thread;
use DeepL\DeepLException;
use DeepL\Translator;
use LanguageDetection\Language;

class Translate extends AbstractAction implements ActionInterface
{
    public const NAME = 'Translate';

    public const ICON = 'fa-solid fa-language';

    public const SHORTCUT = 'CommandOrControl+Shift+T';

    public const CONFIG = null;

    private Translator $translator;

    /**
     * @throws DeepLException
     */
    public function __construct(Assistant $assistant, Action $action, ?Thread $thread, string $input)
    {
        parent::__construct($assistant, $action, $thread, $input);
        $this->translator = new Translator(env('DEEPL_TOKEN'));
    }

    /**
     * @throws DeepLException
     */
    public function execute(): string
    {
        $sourceLang = $this->detectLanguage();
        if ($sourceLang === 'pl') {
            $translatedText = $this->translateFromPolishToEnglish();
        } else {
            $translatedText = $this->translateFromEnglishToPolish();
        }

        return $translatedText;
    }

    private function detectLanguage(): string
    {
        $detector = new Language;
        $result = $detector->detect($this->input)->bestResults()->close();

        return key($result);
    }

    /**
     * @throws DeepLException
     */
    private function translateFromPolishToEnglish(): string
    {
        return $this->translator->translateText($this->input, 'pl', 'en-GB')->text;
    }

    /**
     * @throws DeepLException
     */
    private function translateFromEnglishToPolish(): string
    {
        return $this->translator->translateText($this->input, null, 'pl')->text;
    }
}
