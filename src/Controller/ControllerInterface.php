<?php
declare(strict_types=1);

namespace Mupin\Controller;

use Psr\Http\Message\ServerRequestInterface;

interface ControllerInterface
{
    public function execute(ServerRequestInterface $request);
}
