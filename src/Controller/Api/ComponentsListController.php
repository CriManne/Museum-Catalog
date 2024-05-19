<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Plugins\Http\ResponseFactory;
use App\Plugins\Http\Responses\Ok;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;

class ComponentsListController implements ControllerInterface
{
    public const array CATEGORIES = [
        'Cpu',
        'Ram',
        'Os',
        'PeripheralType',
        'Publisher',
        'Author',
        'SoftwareType',
        'SupportType'
    ];

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return ResponseFactory::create(
            new Ok(json_encode(self::CATEGORIES))
        );
    }
}
