<?php

namespace App\Lib\Assistant\Actions;

use App\Enums\Assistant\ChatModel;
use App\Lib\Interfaces\ActionInterface;
use DeepL\DeepLException;
use DeepL\Translator;
use LanguageDetection\Language;

class Translate implements ActionInterface
{
    /**
     * Initial variables for action
     */
    public const EXAMPLE = [
        "Przetłumacz: Lubię jeść pizzę {\"action\": \"" . self::class . "\"}"
    ];
    public static string $name = 'Translate';
    public static string $icon = 'fa-solid fa-language';
    public static string $shortcut = '';
    public static string $model = ChatModel::GPT3->value;

    protected Translator $translator;
    protected string $text;

    /**
     * @throws DeepLException
     */
    public function __construct()
    {
        $this->translator = new Translator(env('DEEPL_TOKEN'));
    }

    /**
     * @param string $prompt
     * @return void
     */
    public function setPrompt(string $prompt): void
    {
        $this->text = $prompt;
    }

    /**
     * @return string
     * @throws DeepLException
     */
    public function execute(): string
    {
        $sourceLang = $this->detectLanguage($this->text);
        if ($sourceLang === 'pl') {
            $translatedText = $this->translateFromPolishToEnglish($this->text);
        } else {
            $translatedText = $this->translateFromEnglishToPolish($this->text);
        }
        return $translatedText;
    }

    /**
     * @param string $string
     * @return string
     */
    public function detectLanguage(string $string): string
    {
        $detector = new Language();
        $result = $detector->detect($string)->bestResults()->close();
        return key($result);
    }

    /**
     * @param string $text
     * @return string
     * @throws DeepLException
     */
    protected function translateFromPolishToEnglish(string $text): string
    {
        $translationResult = $this->translator->translateText($text, 'pl', 'en-GB');
        return $translationResult->text;
    }

    /**
     * @param string $text
     * @return string
     * @throws DeepLException
     */
    protected function translateFromEnglishToPolish(string $text): string
    {
        $translationResult = $this->translator->translateText($text, null, 'pl');
        return $translationResult->text;
    }
}
