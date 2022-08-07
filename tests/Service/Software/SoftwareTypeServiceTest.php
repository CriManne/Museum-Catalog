<?php

declare(strict_types=1);

namespace App\Test\Service;

use App\Exception\ServiceException;
use App\Model\Software\SoftwareType;
use App\Repository\Software\SoftwareTypeRepository;
use App\Service\Software\SoftwareTypeService;
use PHPUnit\Framework\TestCase;
use PDO;
use PDOStatement;

final class SoftwareTypeServiceTest extends TestCase
{
    public SoftwareTypeService $softwareTypeService;
    
    public function setUp(): void
    {        
        $this->pdo = $this->createMock(PDO::class);
        $this->sth = $this->createMock(PDOStatement::class);
        $this->pdo->method('prepare')->willReturn($this->sth);
        $this->sth->method('execute')->willReturn(true);
        $this->softwareTypeRepository = new SoftwareTypeRepository($this->pdo);    
        $this->softwareTypeService = new SoftwareTypeService($this->softwareTypeRepository);        

        $this->sampleObject = [
            "SoftwareTypeID"=>1,
            "Name"=>'Office'
        ];        
    }
    
    //INSERT TESTS
    
    public function testBadInsert():void{
        $this->expectException(ServiceException::class);
        $this->sth->method('fetch')->willReturn($this->sampleObject);
        $softwareType = new SoftwareType(null,'Office');
        $this->softwareTypeService->insert($softwareType);
    }

    
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->sth->method('fetch')->willReturn($this->sampleObject);
        $this->assertEquals("Office",$this->softwareTypeService->selectById(1)->Name);
    }
    
    public function testBadSelectById(): void
    {
        $this->expectException(ServiceException::class);
        $this->sth->method('fetch')->willReturn(null);
        $this->softwareTypeService->selectById(2);
    }
    
    public function testBadSelectByName(): void
    {
        $this->expectException(ServiceException::class);
        $this->sth->method('fetch')->willReturn(null);
        $this->softwareTypeService->selectByName("WRONG");
    }
    
    //UPDATE TESTS
    public function testBadUpdate():void{
        $this->expectException(ServiceException::class);
        $softwareType = new SoftwareType(1,"Office");
        
        $this->sth->method('fetch')->willReturn(null);
        $this->softwareTypeService->update($softwareType);
    }
    
    //DELETE TESTS
    public function testBadDelete():void{
        $this->expectException(ServiceException::class);
        
        $this->sth->method('fetch')->willReturn(null);
        
        $this->softwareTypeService->delete(5);
    }   
}