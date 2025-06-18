<?php

declare(strict_types=1);

namespace Kirschbaum\Commentions\Livewire;

use Kirschbaum\Commentions\Contracts\RenderableComment;
use Livewire\Mechanisms\HandleComponents\Synthesizers\Synth;

final class RenderableCommentSynth extends Synth
{
    public static $key = 'renderable-comment';

    public static function match($target)
    {
        return $target instanceof RenderableComment;
    }

    public function dehydrate($target)
    {
        return [[
            //
        ], []];
    }

    public function hydrate($value)
    {
        $instance = new RenderableComment();

        return $instance;
    }
}
