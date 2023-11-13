<?php

namespace App\Lib\Assistant\Actions;

use App\Enums\Assistant\ChatModel as Model;
use App\Lib\Assistant\Assistant;
use DeepL\DeepLException;
use DeepL\Translator;
use LanguageDetection\Language;

class Translate extends AbstractAction
{
    public const NAME = 'Translate';
    public const ICON = 'fa-solid fa-language';

    public Assistant $assistant;
    protected Translator $translator;

    protected string $text;

    /**
     * @param Assistant $assistant
     * @throws DeepLException
     */
    public function __construct(Assistant $assistant)
    {
        $this->assistant = $assistant;
        $this->translator = new Translator(env('DEEPL_TOKEN'));
    }

    /**
     * @return void
     * @throws DeepLException
     */
    public function execute(): void
    {
        $sourceLang = $this->detectLanguage();
        if ($sourceLang === 'pl') {
            $translatedText = $this->translateFromPolishToEnglish();
        } else {
            $translatedText = $this->translateFromEnglishToPolish();
        }
        $this->assistant->setResponse($translatedText);
    }

    /**
     * @return string
     */
    public function detectLanguage(): string
    {
        $detector = new Language();
        $result = $detector->detect($this->assistant->query)->bestResults()->close();
        return key($result);
    }

    /**
     * @return string
     * @throws DeepLException
     */
    protected function translateFromPolishToEnglish(): string
    {
        return $this->translator->translateText($this->assistant->query, 'pl', 'en-GB')->text;
    }

    /**
     * @return string
     * @throws DeepLException
     */
    protected function translateFromEnglishToPolish(): string
    {
        return $this->translator->translateText($this->assistant->query, null, 'pl')->text;
    }
}
