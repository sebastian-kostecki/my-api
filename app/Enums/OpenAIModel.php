<?php

namespace App\Enums;

enum OpenAIModel:string {
    case GPT3 = "gpt-3.5-turbo";
    case GPT4 = "gpt-4";
}
