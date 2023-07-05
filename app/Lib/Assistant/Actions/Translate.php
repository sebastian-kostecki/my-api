<?php

namespace App\Lib\Assistant\Actions;

use App\Lib\Interfaces\ActionInterface;
use DeepL\DeepLException;
use DeepL\Translator;
use LanguageDetection\Language;
use OpenAI\Laravel\Facades\OpenAI as Client;

class Translate implements ActionInterface
{
    public static string $name = 'Translate';
    public static string $slug = 'translate';
    public static string $icon = 'fa-solid fa-language';
    public static string $shortcut = '';

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
    public function setMessage(string $prompt): void
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
            $translatedText = $this->improveText($translatedText);
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

    protected function improveText($translatedText)
    {
        $prompt = "You are developing an English text improvement system. ";
        $prompt .= "Given a piece of text written by a non-native English speaker, provide suggestions to enhance the grammar, vocabulary, syntax, and overall clarity of the text. ";
        $prompt .= "Your system should analyze the text and offer constructive feedback on sentence structure, word choice, grammatical errors, and idiomatic expressions. ";
        $prompt .= "Consider the recipient's proficiency in English and aim to provide clear explanations and alternative phrasings to aid their understanding. ";
        $prompt .= "Help the recipient to refine their writing skills by providing specific recommendations that promote more natural and accurate English language usage.";
        $prompt .= "\n###TEXT\n" . $translatedText;

        $response = Client::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'temperature' => 0.8,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prompt
                ],
            ],
        ]);
        return $response->choices[0]->message->content;
    }
}
