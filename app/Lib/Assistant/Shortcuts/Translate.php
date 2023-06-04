<?php

namespace App\Lib\Assistant\Shortcuts;

use DeepL\DeepLException;
use DeepL\Translator;
use LanguageDetection\Language;

class Translate
{
    protected Translator $translator;

    /**
     * @throws DeepLException
     */
    public function __construct()
    {
        $this->translator = new Translator(env('DEEPL_TOKEN'));
    }

    /**
     * @param string $text
     * @param array $options
     * @return string
     * @throws DeepLException
     */
    public function translate(string $text, array $options = []): string
    {
        if (empty($options['sourceLang'])) {
            $sourceLang = $this->detectLanguage($text);
        } else {
            $sourceLang = $options['sourceLang'];
        }
        if ($sourceLang === 'pl' ) {
            $translatedText = $this->translateFromPolishToEnglish($text);
        } else {
            $translatedText = $this->translateFromEnglishToPolish($text);
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
