<?php

namespace App\Lib\Assistant\Actions;

use App\Lib\Interfaces\ActionInterface;
use DeepL\DeepLException;
use DeepL\Translator;
use LanguageDetection\Language;

class Translate implements ActionInterface
{
    public static string $name = 'Translate';
    public static string $slug = 'translate';
    public static string $icon = 'fa-solid fa-language';

    protected Translator $translator;
    protected string $text;
    protected array $options;

    /**
     * @param string $text
     * @param array $options
     * @throws DeepLException
     */
    public function __construct(string $text, array $options = [])
    {
        $this->translator = new Translator(env('DEEPL_TOKEN'));
        $this->text = $text;
        $this->options = $options;
    }

    /**
     * @return string
     * @throws DeepLException
     */
    public function execute(): string
    {
        if (empty($options['sourceLang'])) {
            $sourceLang = $this->detectLanguage($this->text);
        } else {
            $sourceLang = $options['sourceLang'];
        }
        if ($sourceLang === 'pl' ) {
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
