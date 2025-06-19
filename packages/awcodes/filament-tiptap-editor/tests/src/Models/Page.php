<?php

declare(strict_types=1);

namespace FilamentTiptapEditor\Tests\Models;

use FilamentTiptapEditor\Tests\Database\Factories\PageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'json_content' => 'array',
    ];

    protected static function newFactory(): PageFactory
    {
        return new PageFactory;
    }
}
