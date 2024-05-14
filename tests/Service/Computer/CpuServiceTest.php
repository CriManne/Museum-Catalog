<?php

declare(strict_types=1);

namespace App\Test\Service\Computer;

use App\Exception\ServiceException;
use App\Models\Computer\Cpu;
use App\Repository\Computer\CpuRepository;
use App\Service\Computer\CpuService;
use App\Test\Service\BaseServiceTest;

final class CpuServiceTest extends BaseServiceTest
{
    public CpuService $cpuService;
    public CpuRepository $cpuRepository;

    public function setUp(): void
    {
        $this->cpuRepository = $this->createMock(CpuRepository::class);
        $this->cpuService = new CpuService($this->cpuRepository);

        $this->sampleObject = new Cpu(
            modelName: 'Cpu 1.0',
            speed: "4GHZ",
            id: 1
        );
    }

    //INSERT TESTS
    public function testGoodSave(): void
    {
        $this->expectNotToPerformAssertions();
        $this->cpuRepository->method('findFirst')->willReturn(null);
        $cpu = new Cpu('Cpu 1.0', '4GHZ', 1);
        $this->cpuService->save($cpu);
    }

    public function testBadInsert(): void
    {
        $this->expectException(ServiceException::class);
        $this->cpuRepository->method('findFirst')->willReturn($this->sampleObject);
        $cpu = new Cpu('Cpu 1.0', '4GHZ', 1);
        $this->cpuService->save($cpu);
    }


    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->cpuRepository->method('findById')->willReturn($this->sampleObject);
        $this->assertEquals("Cpu 1.0", $this->cpuService->findById(1)->modelName);
    }

    public function testBadSelectById(): void
    {
        $this->expectException(ServiceException::class);
        $this->cpuRepository->method('findById')->willReturn(null);
        $this->cpuService->findById(2);
    }

    //UPDATE TESTS
    public function testBadUpdate(): void
    {
        $this->expectException(ServiceException::class);
        $cpu = new Cpu("Cpu 2.5", "4GHZ", 1);
        $this->cpuRepository->method('findById')->willReturn(null);
        $this->cpuService->update($cpu);
    }

    //DELETE TESTS
    public function testBadDelete(): void
    {
        $this->expectException(ServiceException::class);
        $this->cpuRepository->method('findById')->willReturn(null);
        $this->cpuService->delete(5);
    }
}