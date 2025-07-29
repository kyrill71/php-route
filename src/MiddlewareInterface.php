<?php

declare(strict_types=1);

namespace Kyrill\PhpRoute;

interface MiddlewareInterface
{
    public function handle();
}
