<?php

namespace Kirschbaum\Commentions\Contracts;

use DateTime;
use Carbon\Carbon;

interface RenderableComment
{
    public function isComment(): bool;

    public function getId(): string|int|null;

    public function getAuthorName(): string;

    public function getAuthorAvatar(): ?string;

    public function getBody(): string;

    public function getParsedBody(): string;

    public function getCreatedAt(): DateTime|Carbon;

    public function getUpdatedAt(): DateTime|Carbon;

    public function getLabel(): ?string;

    public function getContentHash(): string;
}
