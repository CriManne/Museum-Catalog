<?php
declare(strict_types=1);

namespace Mupin\Test\Controller;

use League\Plates\Engine;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Mupin\Controller\Error405;

final class Error405Test extends TestCase
{
    public function setUp(): void
    {
        $this->plates = new Engine('src/View');
        $this->error = new Error405($this->plates);
        $this->request = $this->createMock(ServerRequestInterface::class);
    }

    public function testExecuteRender405View(): void
    {
        $this->expectOutputString($this->plates->render('405'));
        $this->error->execute($this->request);

        $this->assertEquals(405, http_response_code());
    }
}
