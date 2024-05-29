<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Plugins\Http\ResponseFactory;
use App\Plugins\Http\Responses\Ok;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;

class ArtifactsListController implements ControllerInterface
{
    public const string COMPUTER   = 'Computer';
    public const string PERIPHERAL = 'Peripheral';
    public const string BOOK       = 'Book';
    public const string MAGAZINE   = 'Magazine';
    public const string SOFTWARE   = 'Software';

    public const array CATEGORIES
        = [
            self::COMPUTER,
            self::PERIPHERAL,
            self::BOOK,
            self::MAGAZINE,
            self:: SOFTWARE
        ];

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return ResponseFactory::create(
            new Ok(json_encode(self::CATEGORIES))
        );
    }
}
