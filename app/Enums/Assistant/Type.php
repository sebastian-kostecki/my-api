<?php

namespace App\Enums\Assistant;

enum Type: string
{
    case QUERY = 'query';
    case SAVE = 'save';
    case FORGET = 'forget';
    case ACTION = 'action';
}
