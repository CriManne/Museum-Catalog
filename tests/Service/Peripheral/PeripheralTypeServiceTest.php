<?php

declare(strict_types=1);

namespace App\Test\Service\Peripheral;

use App\Exception\ServiceException;
use App\Model\Peripheral\PeripheralType;
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
        $this->peripheralTypeRepository = new PeripheralTypeRepository($this->pdo);
        $this->peripheralTypeService = new PeripheralTypeService($this->peripheralTypeRepository);        

        $this->sampleObject = [
            "id"=>1,
            "name"=>'Mouse'
        ];        
    }
    
    //INSERT TESTS
    public function testGoodInsert():void{
        $this->sth->method('fetch')->willReturn($this->sampleObject);
        $this->assertEquals($this->peripheralTypeService->selectById(1)->name,"Mouse");
    }
    
    public function testBadInsert():void{
        $this->expectException(ServiceException::class);
        $this->sth->method('fetch')->willReturn($this->sampleObject);
        $peripheralType = new PeripheralType('Mouse');
        $this->peripheralTypeService->insert($peripheralType);
    }

    
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->sth->method('fetch')->willReturn($this->sampleObject);
        $this->assertEquals("Mouse",$this->peripheralTypeService->selectById(1)->name);
    }
    
    public function testBadSelectById(): void
    {
        $this->expectException(ServiceException::class);
        $this->sth->method('fetch')->willReturn(null);
        $this->peripheralTypeService->selectById(2);
    }
    
    public function testBadSelectByName(): void
    {
        $this->expectException(ServiceException::class);
        $this->sth->method('fetch')->willReturn(null);
        $this->peripheralTypeService->selectByName("Mouse");
    }
    
    //UPDATE TESTS
    public function testBadUpdate():void{
        $this->expectException(ServiceException::class);
        $peripheralType = new PeripheralType("Keyboard",1);
        
        $this->sth->method('fetch')->willReturn(null);
        $this->peripheralTypeService->update($peripheralType);
    }
    
    //DELETE TESTS
    public function testBadDelete():void{
        $this->expectException(ServiceException::class);
        
        $this->sth->method('fetch')->willReturn(null);
        
        $this->peripheralTypeService->delete(5);
    }   
}