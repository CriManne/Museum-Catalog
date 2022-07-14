<?php
declare(strict_types=1);

namespace Mupin\Test\Controller;

use League\Plates\Engine;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Mupin\Controller\Home;

final class HomeTest extends TestCase
{
    public function setUp(): void
    {
        $this->plates = new Engine('src/View');
        $this->home = new Home($this->plates);
        $this->request = $this->createMock(ServerRequestInterface::class);
    }

    public function testExecuteRenderHomeView(): void
    {
        $this->expectOutputString($this->plates->render('home'));
        $this->home->execute($this->request);
    }
}
