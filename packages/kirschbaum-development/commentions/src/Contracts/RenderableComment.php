<?php

declare(strict_types=1);

namespace Kirschbaum\Commentions\Contracts;

use Carbon\Carbon;
use DateTimeImmutable;

interface RenderableComment
{
    public function isComment(): bool;

    public function getId(): string|int|null;

    public function getAuthorName(): string;

    public function getAuthorAvatar(): ?string;

    public function getBody(): string;

    public function getParsedBody(): string;

    public function getCreatedAt(): DateTimeImmutable|Carbon;

    public function getUpdatedAt(): DateTimeImmutable|Carbon;

    public function getLabel(): ?string;

    public function getContentHash(): string;
}
