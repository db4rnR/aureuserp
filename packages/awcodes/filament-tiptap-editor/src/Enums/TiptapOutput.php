<?php

declare(strict_types=1);

namespace FilamentTiptapEditor\Enums;

enum TiptapOutput: string
{
    case Html = 'html';
    case Json = 'json';
    case Text = 'text';
}
