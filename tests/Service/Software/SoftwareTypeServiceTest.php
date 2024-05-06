<?php

declare(strict_types=1);

namespace App\Test\Service\Software;

use App\Exception\ServiceException;
use App\Model\Software\SoftwareType;
use App\Repository\Software\SoftwareTypeRepository;
use App\Service\Software\SoftwareTypeService;
use App\Test\Service\BaseServiceTest;
use PHPUnit\Framework\TestCase;
use PDO;
use PDOStatement;

final class SoftwareTypeServiceTest extends BaseServiceTest
{
    public SoftwareTypeService $softwareTypeService;
    public SoftwareTypeRepository $softwareTypeRepository;

    public function setUp(): void
    {        
        $this->softwareTypeRepository = $this->createMock(SoftwareTypeRepository::class);
        $this->softwareTypeService = new SoftwareTypeService($this->softwareTypeRepository);        

        $this->sampleObject = new SoftwareType(
            name: 'Office',
            id: 1
        );
    }
    
    //INSERT TESTS
    public function testGoodInsert():void{
        $this->expectNotToPerformAssertions();
        $this->softwareTypeRepository->method('findFirst')->willReturn(null);
        $softwareType = new SoftwareType('Office');
        $this->softwareTypeService->save($softwareType);
    }

    public function testBadInsert():void{
        $this->expectException(ServiceException::class);
        $this->softwareTypeRepository->method('findFirst')->willReturn($this->sampleObject);
        $softwareType = new SoftwareType('Office');
        $this->softwareTypeService->save($softwareType);
    }

    
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->softwareTypeRepository->method('findById')->willReturn($this->sampleObject);
        $this->assertEquals("Office",$this->softwareTypeService->findById(1)->name);
    }
    
    public function testBadSelectById(): void
    {
        $this->expectException(ServiceException::class);
        $this->softwareTypeRepository->method('findFirst')->willReturn(null);
        $this->softwareTypeService->findById(2);
    }
    
    public function testBadSelectByName(): void
    {
        $this->expectException(ServiceException::class);
        $this->softwareTypeRepository->method('findFirst')->willReturn(null);
        $this->softwareTypeService->findByName("WRONG");
    }
    
    //UPDATE TESTS
    public function testBadUpdate():void{
        $this->expectException(ServiceException::class);
        $softwareType = new SoftwareType("Office",1);
        $this->softwareTypeRepository->method('findFirst')->willReturn(null);
        $this->softwareTypeService->update($softwareType);
    }
    
    //DELETE TESTS
    public function testBadDelete():void{
        $this->expectException(ServiceException::class);

        $this->softwareTypeRepository->method('findFirst')->willReturn(null);
        $this->softwareTypeService->delete(5);
    }   
}