<?php
declare(strict_types=1);

namespace App\Test\Service\Computer;

use App\Models\GenericObject;
use App\Repository\Computer\CpuRepository;
use App\Repository\Computer\OsRepository;
use App\Repository\Computer\RamRepository;
use App\Repository\GenericObjectRepository;
use App\Test\Service\BaseServiceTest;
use App\Exception\ServiceException;
use App\Models\Computer\Os;
use App\Models\Computer\Ram;
use App\Models\Computer\Cpu;
use App\Models\Computer\Computer;
use App\Repository\Computer\ComputerRepository;
use App\Service\Computer\ComputerService;

final class ComputerServiceTest extends BaseServiceTest
{
    public GenericObjectRepository $genericObjectRepository;
    public ComputerRepository      $computerRepository;
    public CpuRepository           $cpuRepository;
    public OsRepository            $osRepository;
    public RamRepository           $ramRepository;
    public ComputerService         $computerService;


    public function setUp(): void
    {
        $this->genericObjectRepository = $this->createMock(GenericObjectRepository::class);
        $this->computerRepository = $this->createMock(ComputerRepository::class);
        $this->cpuRepository = $this->createMock(CpuRepository::class);
        $this->osRepository = $this->createMock(OsRepository::class);
        $this->ramRepository = $this->createMock(RamRepository::class);

        $this->computerService = new ComputerService(
            genericObjectRepository: $this->genericObjectRepository,
            computerRepository: $this->computerRepository,
            cpuRepository: $this->cpuRepository,
            osRepository: $this->osRepository,
            ramRepository: $this->ramRepository
        );

        $this->sampleGenericObject = new GenericObject(
            "objID",
            null,
            null,
            null
        );

        $this->sampleObject = new Computer(
            $this->sampleGenericObject,
            'Computer 1',
            2005,
            "1TB",
            new Cpu('Cpu 1.0', '2GHZ', 1),
            new Ram('Ram 1.0', '64GB', 1),
            new Os('Windows', 1)
        );
    }


    //INSERT TESTS
    public function testBadInsert(): void
    {
        $this->expectException(ServiceException::class);
        $this->computerRepository->method('findFirst')->willReturn($this->sampleObject);
        $this->computerService->save($this->sampleObject);
    }

    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->computerRepository->method('findById')->willReturn($this->sampleObject);
        $this->assertEquals("Computer 1", $this->computerService->findById("ObjID")->modelName);
    }

    public function testBadSelectById(): void
    {
        $this->expectException(ServiceException::class);
        $this->computerRepository->method('findById')->willReturn(null);
        $this->computerService->findById("ObjID25");
    }

    //UPDATE TESTS
    public function testBadUpdate(): void
    {
        $this->expectException(ServiceException::class);
        $this->computerRepository->method('findById')->willReturn(null);
        $this->computerService->update($this->sampleObject);
    }

    //DELETE TESTS
    public function testBadDelete(): void
    {
        $this->expectException(ServiceException::class);

        $this->computerRepository->method('findById')->willReturn(null);

        $this->computerService->delete("ObjID99");
    }
}
