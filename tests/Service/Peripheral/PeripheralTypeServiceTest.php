<?php

declare(strict_types=1);

namespace App\Test\Service;

use App\Exception\ServiceException;
use App\Model\Peripheral\PeripheralType;
use App\Repository\Peripheral\PeripheralTypeRepository;
use App\Service\Peripheral\PeripheralTypeService;
use PHPUnit\Framework\TestCase;
use PDO;
use PDOStatement;

final class PeripheralTypeServiceTest extends TestCase
{
    public PeripheralTypeService $peripheralTypeService;
    
    public function setUp(): void
    {        
        $this->pdo = $this->createMock(PDO::class);
        $this->sth = $this->createMock(PDOStatement::class);
        $this->pdo->method('prepare')->willReturn($this->sth);
        $this->sth->method('execute')->willReturn(true);
        $this->peripheralTypeRepository = new PeripheralTypeRepository($this->pdo);    
        $this->peripheralTypeService = new PeripheralTypeService($this->peripheralTypeRepository);        

        $this->sampleObject = [
            "PeripheralTypeID"=>1,
            "Name"=>'Mouse'
        ];        
    }
    
    //INSERT TESTS
    public function testGoodInsert():void{
        $this->sth->method('fetch')->willReturn($this->sampleObject);
        $this->assertEquals($this->peripheralTypeService->selectById(1)->Name,"Mouse");        
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
        $this->assertEquals("Mouse",$this->peripheralTypeService->selectById(1)->Name);
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