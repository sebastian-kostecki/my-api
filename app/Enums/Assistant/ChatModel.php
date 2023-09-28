<?php

namespace App\Enums\Assistant;

enum ChatModel:string {
    case GPT3 = "gpt-3.5-turbo";
    case GPT4 = "gpt-4";
}
