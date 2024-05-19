<?php

declare(strict_types=1);

namespace App\Test\Service\Software;

use App\Exception\ServiceException;
use App\Models\Software\SupportType;
use App\Repository\Software\SupportTypeRepository;
use App\Service\Software\SupportTypeService;
use App\Test\Service\BaseServiceTest;

final class SupportTypeServiceTest extends BaseServiceTest
{
    public SupportTypeService $supportTypeService;
    public SupportTypeRepository $supportTypeRepository;
    
    public function setUp(): void
    {        
        $this->supportTypeRepository = $this->createMock(SupportTypeRepository::class);
        $this->supportTypeService = new SupportTypeService($this->supportTypeRepository);        

        $this->sampleObject = new SupportType(
            name: 'CD-ROM',
            id: 1
        );
    }
    
    //INSERT TESTS    
    public function testGoodInsert():void{
        $this->expectNotToPerformAssertions();
        $this->supportTypeRepository->method('findFirst')->willReturn(null);
        $supportType = new SupportType("FLOPPY",1);
        $this->supportTypeService->save($supportType);
    }

    public function testBadInsert():void{
        $this->expectException(ServiceException::class);
        $this->supportTypeRepository->method('findFirst')->willReturn($this->sampleObject);
        $supportType = new SupportType("FLOPPY",1);
        $this->supportTypeService->save($supportType);
    }

    
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->supportTypeRepository->method('findById')->willReturn($this->sampleObject);
        $this->assertEquals("CD-ROM",$this->supportTypeService->findById(1)->name);
    }
    
    public function testBadSelectById(): void
    {
        $this->expectException(ServiceException::class);
        $this->supportTypeRepository->method('findById')->willReturn(null);
        $this->supportTypeService->findById(2);
    }

    public function testBadSelectByName(): void
    {
        $this->expectException(ServiceException::class);
        $this->supportTypeRepository->method('findFirst')->willReturn(null);
        $this->supportTypeService->findByName("CD-ROM");
    }
    
    //UPDATE TESTS
    public function testBadUpdate():void{
        $this->expectException(ServiceException::class);
        $supportType = new SupportType("FLOPPY",1);
        
        $this->supportTypeRepository->method('findFirst')->willReturn(null);
        $this->supportTypeService->update($supportType);
    }
    
    //DELETE TESTS
    public function testBadDelete():void{
        $this->expectException(ServiceException::class);
        
        $this->supportTypeRepository->method('findFirst')->willReturn(null);
        
        $this->supportTypeService->delete(5);
    }   
}