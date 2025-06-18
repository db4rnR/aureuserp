<?php

declare(strict_types=1);

namespace Kirschbaum\Commentions\Livewire;

use Illuminate\Database\Eloquent\Model;
use Kirschbaum\Commentions\Actions\SaveComment;
use Kirschbaum\Commentions\Config;
use Kirschbaum\Commentions\Livewire\Concerns\HasMentions;
use Kirschbaum\Commentions\Livewire\Concerns\HasPolling;
use Livewire\Attributes\On;
use Livewire\Attributes\Renderless;
use Livewire\Component;

final class Comments extends Component
{
    use HasMentions;
    use HasPolling;

    public Model $record;

    public string $commentBody = '';

    protected $rules = [
        'commentBody' => 'required|string',
    ];

    #[Renderless]
    public function save(): void
    {
        $this->validate();

        SaveComment::run(
            $this->record,
            Config::resolveAuthenticatedUser(),
            $this->commentBody
        );

        $this->clear();
        $this->dispatch('comment:saved');
    }

    public function render()
    {
        return view('commentions::comments');
    }

    #[On('body:updated')]
    #[Renderless]
    public function updateCommentBodyContent($value): void
    {
        $this->commentBody = $value;
    }

    #[Renderless]
    public function clear(): void
    {
        $this->commentBody = '';

        $this->dispatch('comments:content:cleared');
    }
}
