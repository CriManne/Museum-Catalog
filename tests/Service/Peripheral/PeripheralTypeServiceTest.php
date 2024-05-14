<?php

declare(strict_types=1);

namespace App\Test\Service\Peripheral;

use App\Exception\ServiceException;
use App\Models\Computer\Ram;
use App\Models\Peripheral\PeripheralType;
use App\Repository\Peripheral\PeripheralTypeRepository;
use App\Service\Peripheral\PeripheralTypeService;
use App\Test\Service\BaseServiceTest;
use PHPUnit\Framework\TestCase;
use PDO;
use PDOStatement;

final class PeripheralTypeServiceTest extends BaseServiceTest
{
    public PeripheralTypeService $peripheralTypeService;
    public PeripheralTypeRepository $peripheralTypeRepository;
    
    public function setUp(): void
    {        
        $this->peripheralTypeRepository = $this->createMock(PeripheralTypeRepository::class);
        $this->peripheralTypeService = new PeripheralTypeService($this->peripheralTypeRepository);        

        $this->sampleObject = new PeripheralType(
            name: 'Mouse',
            id: 1,
        );
    }
    
    //INSERT TESTS
    public function testGoodInsert():void{
        $this->expectNotToPerformAssertions();
        $this->peripheralTypeRepository->method('findFirst')->willReturn(null);
        $peripheralType = new PeripheralType('Mouse');
        $this->peripheralTypeService->save($peripheralType);
    }
    
    public function testBadInsert():void{
        $this->expectException(ServiceException::class);
        $this->peripheralTypeRepository->method('findFirst')->willReturn($this->sampleObject);
        $peripheralType = new PeripheralType('Mouse');
        $this->peripheralTypeService->save($peripheralType);
    }

    
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->peripheralTypeRepository->method('findById')->willReturn($this->sampleObject);
        $this->assertEquals("Mouse",$this->peripheralTypeService->findById(1)->name);
    }
    
    public function testBadSelectById(): void
    {
        $this->expectException(ServiceException::class);
        $this->peripheralTypeRepository->method('findById')->willReturn(null);
        $this->peripheralTypeService->findById(2);
    }
    
    public function testBadSelectByName(): void
    {
        $this->expectException(ServiceException::class);
        $this->peripheralTypeRepository->method('findById')->willReturn(null);
        $this->peripheralTypeService->findByName("Mouse");
    }
    
    //UPDATE TESTS
    public function testBadUpdate():void{
        $this->expectException(ServiceException::class);
        $peripheralType = new PeripheralType("Keyboard",1);

        $this->peripheralTypeRepository->method('findById')->willReturn(null);
        $this->peripheralTypeService->update($peripheralType);
    }
    
    //DELETE TESTS
    public function testBadDelete():void{
        $this->expectException(ServiceException::class);

        $this->peripheralTypeRepository->method('findById')->willReturn(null);
        
        $this->peripheralTypeService->delete(5);
    }   
}