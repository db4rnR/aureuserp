<?php

declare(strict_types=1);

namespace Kirschbaum\Commentions\Livewire\Concerns;

use Illuminate\Support\Collection;
use Kirschbaum\Commentions\Contracts\Commenter;
use Kirschbaum\Commentions\Manager;
use Livewire\Attributes\Computed;

trait HasMentions
{
    /**
     * @var Commenter[]
     */
    public array|Collection|null $mentionables = [];

    #[Computed]
    public function mentions()
    {
        return collect($this->mentionables)
            ->map(function ($mentionable) {
                return is_array($mentionable) ?
                    [
                        'id' => data_get($mentionable, 'id'),
                        'name' => data_get($mentionable, 'name'),
                    ] :
                    [
                        'id' => $mentionable->getKey(),
                        'name' => Manager::getName($mentionable),
                    ];
            });
    }
}
