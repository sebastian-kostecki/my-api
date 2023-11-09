<?php

namespace App\Enums\Assistant;

enum ChatModel:string {
    case GPT3 = "gpt-3.5-turbo-1106";
    case GPT4 = "gpt-4-1106-preview";
}
