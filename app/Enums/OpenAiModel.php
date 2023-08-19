<?php

namespace App\Enums;

enum OpenAiModel:string {
    case GPT3 = "gpt-3.5-turbo";
    case GPT4 = "gpt-4";
}
