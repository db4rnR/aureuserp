<?php

declare(strict_types=1);

namespace Kirschbaum\Commentions;

use Carbon\Carbon;
use DateTimeImmutable;
use Kirschbaum\Commentions\Contracts\RenderableComment as RenderableCommentContract;
use Livewire\Wireable;

class RenderableComment implements RenderableCommentContract, Wireable
{
    private bool $isComment;

    private string|int $id;

    private ?string $authorName;

    private ?string $authorAvatar;

    private string $body;

    private ?string $parsedBody;

    private ?string $label;

    private DateTimeImmutable|Carbon $createdAt;

    private DateTimeImmutable|Carbon $updatedAt;

    public function __construct(
        string|int $id,
        ?string $authorName,
        string $body,
        ?string $authorAvatar = null,
        DateTimeImmutable|Carbon $createdAt = new Carbon(),
        DateTimeImmutable|Carbon $updatedAt = new Carbon(),
        bool $isComment = false,
        ?string $parsedBody = null,
        ?string $label = null,
    ) {
        $this->isComment = $isComment;
        $this->id = $id;
        $this->authorName = $authorName;
        $this->authorAvatar = $authorAvatar;
        $this->body = $body;
        $this->parsedBody = $parsedBody;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->label = $label;
    }

    public static function fromLivewire($value)
    {
        return new self(
            isComment: $value['isComment'],
            id: $value['id'],
            authorName: $value['authorName'],
            authorAvatar: $value['authorAvatar'],
            body: $value['body'],
            parsedBody: $value['parsedBody'],
            createdAt: new Carbon($value['createdAt']),
            updatedAt: new Carbon($value['updatedAt']),
            label: $value['label'],
        );
    }

    public function isComment(): bool
    {
        return $this->isComment;
    }

    public function getId(): string|int|null
    {
        return $this->id;
    }

    public function getAuthorName(): string
    {
        return $this->authorName;
    }

    public function getAuthorAvatar(): ?string
    {
        return $this->authorAvatar;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getParsedBody(): string
    {
        return $this->parsedBody ?? $this->body;
    }

    public function getCreatedAt(): DateTimeImmutable|Carbon
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable|Carbon
    {
        return $this->updatedAt;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function getContentHash(): string
    {
        return "comment-$this->id";
    }

    public function toLivewire()
    {
        return [
            'isComment' => $this->isComment,
            'id' => $this->id,
            'authorName' => $this->authorName,
            'authorAvatar' => $this->authorAvatar,
            'body' => $this->body,
            'parsedBody' => $this->parsedBody,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d H:i:s'),
            'label' => $this->label,
        ];
    }
}
