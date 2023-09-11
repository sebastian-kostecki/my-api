<?php

namespace App\Enums\Assistant;

enum Type: string
{
    case QUERY = 'query';
    case REMEMBER = 'remember';
    case FORGET = 'forget';
    case ACTION = 'action';
}
