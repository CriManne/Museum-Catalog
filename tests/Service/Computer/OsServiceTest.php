<?php

declare(strict_types=1);

namespace App\Test\Service\Computer;

use App\Exception\ServiceException;
use App\Models\Computer\Os;
use App\Repository\Computer\OsRepository;
use App\Service\Computer\OsService;
use App\Test\Service\BaseServiceTest;
use PHPUnit\Framework\TestCase;
use PDO;
use PDOStatement;

final class OsServiceTest extends BaseServiceTest
{
    public OsService $osService;
    public OsRepository $osRepository;

    public function setUp(): void
    {
        $this->osRepository = $this->createMock(OsRepository::class);
        $this->osService = new OsService($this->osRepository);

        $this->sampleObject = new Os(
            name: "Windows",
            id: 1
        );
    }

    //INSERT TESTS
    public function testGoodInsert(): void
    {
        $this->expectNotToPerformAssertions();
        $this->osRepository->method('findFirst')->willReturn(null);
        $os = new Os('Windows');
        $this->osService->save($os);
    }

    public function testBadInsert(): void
    {
        $this->expectException(ServiceException::class);
        $this->osRepository->method('findFirst')->willReturn($this->sampleObject);
        $os = new Os('Windows');
        $this->osService->save($os);
    }

    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->osRepository->method('findById')->willReturn($this->sampleObject);
        $this->assertEquals("Windows", $this->osService->findById(1)->name);
    }

    public function testBadSelectById(): void
    {
        $this->expectException(ServiceException::class);
        $this->osRepository->method('findById')->willReturn(null);
        $this->osService->findById(2);
    }

    //UPDATE TESTS
    public function testBadUpdate(): void
    {
        $this->expectException(ServiceException::class);
        $os = new Os("Linux", 1);
        $this->osRepository->method('findById')->willReturn(null);
        $this->osService->update($os);
    }

    //DELETE TESTS
    public function testBadDelete(): void
    {
        $this->expectException(ServiceException::class);

        $this->osRepository->method('findById')->willReturn(null);

        $this->osService->delete(5);
    }
}