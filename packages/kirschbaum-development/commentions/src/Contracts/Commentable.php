<?php

declare(strict_types=1);

namespace Kirschbaum\Commentions\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @mixin Model
 */
interface Commentable
{
    public function comments(): MorphMany;

    /**
     * Get the identifier key for the object. Usually the primary key.
     */
    public function getKey(): int|string|null;

    public function getMorphClass(): string;
}
