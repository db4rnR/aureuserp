<?php

declare(strict_types=1);

namespace Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kirschbaum\Commentions\Contracts\Commentable;
use Kirschbaum\Commentions\HasComments;
use Tests\Database\Factories\PostFactory;

class Post extends Model implements Commentable
{
    use HasComments;
    use HasFactory;

    protected $guarded = [];

    protected static function newFactory(): PostFactory
    {
        return new PostFactory();
    }
}
