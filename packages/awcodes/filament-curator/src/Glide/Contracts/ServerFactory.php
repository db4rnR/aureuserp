<?php

declare(strict_types=1);

namespace Awcodes\Curator\Glide\Contracts;

use League\Glide\Server;
use League\Glide\ServerFactory as GlideServerFactory;

interface ServerFactory
{
    public function getFactory(): GlideServerFactory|Server;
}
