<?php
declare(strict_types=1);

namespace App\Test\Service\Software;

use App\Models\GenericObject;
use App\Repository\Computer\OsRepository;
use App\Repository\GenericObjectRepository;
use App\Repository\Software\SoftwareTypeRepository;
use App\Repository\Software\SupportTypeRepository;
use App\Test\Service\BaseServiceTest;
use App\Exception\ServiceException;

use App\Models\Software\Software;
use App\Models\Software\SoftwareType;
use App\Models\Software\SupportType;
use App\Models\Computer\Os;

use App\Repository\Software\SoftwareRepository;
use App\Service\Software\SoftwareService;

final class SoftwareServiceTest extends BaseServiceTest
{
    public GenericObjectRepository $genericObjectRepository;
    public OsRepository            $osRepository;
    public SoftwareTypeRepository  $softwareTypeRepository;
    public SupportTypeRepository   $supportTypeRepository;
    public SoftwareRepository      $softwareRepository;
    public SoftwareService         $softwareService;


    public function setUp(): void
    {
        $this->softwareRepository      = $this->createMock(SoftwareRepository::class);
        $this->genericObjectRepository = $this->createMock(GenericObjectRepository::class);
        $this->softwareTypeRepository  = $this->createMock(SoftwareTypeRepository::class);
        $this->supportTypeRepository   = $this->createMock(SupportTypeRepository::class);
        $this->osRepository            = $this->createMock(OsRepository::class);

        $this->softwareService = new SoftwareService(
            genericObjectRepository: $this->genericObjectRepository,
            softwareRepository: $this->softwareRepository,
            softwareTypeRepository: $this->softwareTypeRepository,
            supportTypeRepository: $this->supportTypeRepository,
            osRepository: $this->osRepository
        );

        $this->sampleGenericObject = new GenericObject("objID");

        $this->sampleObject = new Software(
            $this->sampleGenericObject,
            'Paint',
            new Os('Windows', 1),
            new SoftwareType('Office', 1),
            new SupportType('Support', 1),
        );
    }


    //INSERT TESTS

    public function testBadInsert(): void
    {
        $this->expectException(ServiceException::class);
        $this->softwareRepository->method('findFirst')->willReturn($this->sampleObject);
        $this->softwareService->save($this->sampleObject);
    }

    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->softwareRepository->method('findById')->willReturn($this->sampleObject);
        $this->assertEquals("Paint", $this->softwareService->findById("ObjID")->title);
    }

    public function testBadSelectById(): void
    {
        $this->expectException(ServiceException::class);
        $this->softwareRepository->method('findById')->willReturn(null);
        $this->softwareService->findById("ObjID25");
    }

    //UPDATE TESTS
    public function testBadUpdate(): void
    {
        $this->expectException(ServiceException::class);
        $this->softwareRepository->method('findById')->willReturn(null);
        $this->softwareService->update($this->sampleObject);
    }

    //DELETE TESTS
    public function testBadDelete(): void
    {
        $this->expectException(ServiceException::class);

        $this->softwareRepository->method('findById')->willReturn(null);

        $this->softwareService->delete("ObjID99");
    }
}
