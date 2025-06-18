<?php

declare(strict_types=1);

namespace Kirschbaum\Commentions\Contracts;

interface Commenter
{
    /**
     * Get the identifier key for the object. Usually the primary key.
     */
    public function getKey(): int|string|null;

    public function getMorphClass(): string;
}
