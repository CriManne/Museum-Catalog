<?php
declare(strict_types=1);

namespace App\Test\Service\Peripheral;

use App\Models\GenericObject;
use App\Repository\GenericObjectRepository;
use App\Repository\Peripheral\PeripheralTypeRepository;
use App\Test\Service\BaseServiceTest;
use App\Exception\ServiceException;

use App\Models\Peripheral\Peripheral;
use App\Models\Peripheral\PeripheralType;

use App\Repository\Peripheral\PeripheralRepository;
use App\Service\Peripheral\PeripheralService;

final class PeripheralServiceTest extends BaseServiceTest
{
    public GenericObjectRepository $genericObjectRepository;
    public PeripheralTypeRepository $peripheralTypeRepository;
    public PeripheralRepository $peripheralRepository;
    public PeripheralService $peripheralService;
    

    public function setUp(): void
    {
        $this->genericObjectRepository = $this->createMock(GenericObjectRepository::class);
        $this->peripheralTypeRepository = $this->createMock(PeripheralTypeRepository::class);
        $this->peripheralRepository = $this->createMock(PeripheralRepository::class);

        $this->peripheralService = new PeripheralService(
            $this->genericObjectRepository,
            $this->peripheralRepository,
            $this->peripheralTypeRepository
        );

        $this->sampleGenericObject = new GenericObject("objID");
        $this->sampleObject = new Peripheral(
            $this->sampleGenericObject,
            'Peripheral 1.0',
            new PeripheralType('PeripheralType 1',1)
        );
    }
    
    //INSERT TESTS
    
    public function testBadInsert():void{
        $this->expectException(ServiceException::class);
        $this->peripheralRepository->method('findFirst')->willReturn($this->sampleObject);
        $this->peripheralService->save($this->sampleObject);
    }
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->peripheralRepository->method('findById')->willReturn($this->sampleObject);
        $this->assertEquals("Peripheral 1.0",$this->peripheralService->findById("ObjID")->modelName);
    }
    
    public function testBadSelectById(): void
    {
        $this->expectException(ServiceException::class);
        $this->peripheralRepository->method('findById')->willReturn(null);
        $this->peripheralService->findById("ObjID25");
    }
    
    public function testBadSelectByName(): void
    {
        $this->expectException(ServiceException::class);
        $this->peripheralRepository->method('findFirst')->willReturn(null);
        $this->peripheralService->findByName("WRONG");
    }
    
    //UPDATE TESTS
    public function testBadUpdate():void{
        $this->expectException(ServiceException::class);                
        $this->peripheralRepository->method('findById')->willReturn(null);
        $this->peripheralService->update($this->sampleObject);
    }
    
    //DELETE TESTS
    public function testBadDelete():void{
        $this->expectException(ServiceException::class);
        
        $this->peripheralRepository->method('findById')->willReturn(null);
        
        $this->peripheralService->delete("ObjID99");
    }       
}
