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
    public const string CPU             = 'Cpu';
    public const string RAM             = 'Ram';
    public const string OS              = 'Os';
    public const string PERIPHERAL_TYPE = 'PeripheralType';
    public const string PUBLISHER       = 'Publisher';
    public const string AUTHOR          = 'Author';
    public const string SOFTWARE_TYPE   = 'SoftwareType';
    public const string SUPPORT_TYPE    = 'SupportType';

    public const array CATEGORIES
        = [
            self::CPU,
            self::RAM,
            self::OS,
            self::PERIPHERAL_TYPE,
            self::PUBLISHER,
            self::AUTHOR,
            self::SOFTWARE_TYPE,
            self::SUPPORT_TYPE
        ];

    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return ResponseFactory::create(
            new Ok(json_encode(self::CATEGORIES))
        );
    }
}
