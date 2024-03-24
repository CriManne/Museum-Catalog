<?php
declare(strict_types=1);

namespace App\Test\Service\Peripheral;

use App\Test\Service\BaseServiceTest;
use PHPUnit\Framework\TestCase;
use App\Exception\ServiceException;

use App\Model\Peripheral\Peripheral;
use App\Model\Peripheral\PeripheralType;

use App\Repository\Peripheral\PeripheralRepository;
use App\Service\Peripheral\PeripheralService;

final class PeripheralServiceTest extends BaseServiceTest
{
    public PeripheralRepository $peripheralRepository;
    public PeripheralService $peripheralService;
    

    public function setUp(): void
    {                
        $this->peripheralRepository = $this->createMock(PeripheralRepository::class);

        $this->peripheralService = new PeripheralService($this->peripheralRepository);        

        $this->sampleObject = new Peripheral(
            "objID",
            'Peripheral 1.0',
            new PeripheralType('PeripheralType 1',1),
            null,
            null,
            null,
        );

        $this->sampleObjectRaw = [
            'modelName' => 'Peripheral 1.0',
            'peripheralTypeId' => 1,
            'objectId' => 'objID',
            'note' => null,
            'url' => null,
            'tag' => null,
            'active' => '1'
        ];        
    }
    
    
    //INSERT TESTS
    
    public function testBadInsert():void{
        $this->expectException(ServiceException::class);
        $this->peripheralRepository->method('findByModelName')->willReturn($this->sampleObject);
        $this->peripheralService->insert($this->sampleObject);
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
        $this->peripheralRepository->method('findByModelName')->willReturn(null);
        $this->peripheralService->findByModelName("WRONG");
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
