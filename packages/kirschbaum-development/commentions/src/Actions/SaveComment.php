<?php

declare(strict_types=1);

namespace Kirschbaum\Commentions\Actions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Kirschbaum\Commentions\Comment;
use Kirschbaum\Commentions\Config;
use Kirschbaum\Commentions\Contracts\Commenter;
use Kirschbaum\Commentions\Events\UserWasMentionedEvent;

class SaveComment
{
    /**
     * @throws AuthorizationException
     */
    public function __invoke(Model $commentable, Commenter $author, string $body): Comment
    {
        if ($author->cannot('create', Config::getCommentModel())) {
            throw new AuthorizationException('Cannot create comment');
        }

        $comment = $commentable->comments()->create([
            'body' => $body,
            'author_id' => $author->getKey(),
            'author_type' => $author->getMorphClass(),
        ]);

        $this->dispatchMentionEvents($comment);

        return $comment;
    }

    public static function run(...$args)
    {
        return (new self())(...$args);
    }

    private function dispatchMentionEvents(Comment $comment): void
    {
        $mentionees = $comment->getMentioned();

        $mentionees->each(function ($mentionee) use ($comment): void {
            UserWasMentionedEvent::dispatch($comment, $mentionee);
        });
    }
}
