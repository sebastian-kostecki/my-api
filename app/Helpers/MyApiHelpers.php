<?php

use LanguageDetection\Language;

function detectLanguage($text): int|string|null
{
    $detector = new Language();
    $result = $detector->detect($text)->bestResults()->close();
    return key($result);
}

function returnJson($text): string
{
    preg_match('/\{.*?\}/s', $text, $json);
    return $json[0];
}
