<?php

namespace App\Lib\Assistant\Actions;

use App\Enums\Assistant\ChatModel as Model;
use App\Lib\Assistant\Assistant;
use App\Lib\Interfaces\ActionInterface;
use DeepL\DeepLException;
use DeepL\Translator;
use LanguageDetection\Language;

class Translate extends AbstractAction implements ActionInterface
{
    public const NAME = 'Translate';
    public const ICON = 'fa-solid fa-language';

    public Assistant $assistant;
    protected Translator $translator;

    protected string $text;

    public static array $configFields = [
        'name' => [
            'name' => 'name',
            'label' => 'Name',
            'type' => 'text',
            'default' => self::NAME
        ],
        'icon' => [
            'name' => 'icon',
            'label' => 'Icon',
            'type' => 'text',
            'default' => self::ICON
        ],
    ];

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
        $result = $detector->detect($this->assistant->getQuery())->bestResults()->close();
        return key($result);
    }

    /**
     * @return string
     * @throws DeepLException
     */
    protected function translateFromPolishToEnglish(): string
    {
        return $this->translator->translateText($this->assistant->getQuery(), 'pl', 'en-GB')->text;
    }

    /**
     * @return string
     * @throws DeepLException
     */
    protected function translateFromEnglishToPolish(): string
    {
        return $this->translator->translateText($this->assistant->getQuery(), null, 'pl')->text;
    }
}
