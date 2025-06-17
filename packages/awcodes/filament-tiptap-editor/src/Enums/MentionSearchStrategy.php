<?php

namespace FilamentTiptapEditor\Enums;

enum MentionSearchStrategy: string
{
    case StartsWith = 'starts_with';

    case Tokenized = 'tokenized';
}
