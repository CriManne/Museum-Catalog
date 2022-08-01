<?php

declare(strict_types=1);

namespace App\Test\Repository;

use App\Exception\ServiceException;
use App\Model\Software\SupportType;
use App\Repository\Software\SupportTypeRepository;
use App\Service\Software\SupportTypeService;
use PHPUnit\Framework\TestCase;
use PDO;
use PDOStatement;

final class SupportTypeServiceTest extends TestCase
{
    public SupportTypeService $supportTypeService;
    
    public function setUp(): void
    {        
        $this->pdo = $this->createMock(PDO::class);
        $this->sth = $this->createMock(PDOStatement::class);
        $this->pdo->method('prepare')->willReturn($this->sth);
        $this->sth->method('execute')->willReturn(true);
        $this->supportTypeRepository = new SupportTypeRepository($this->pdo);    
        $this->supportTypeService = new SupportTypeService($this->supportTypeRepository);        

        $this->sampleObject = [
            "SupportTypeID"=>1,
            "Name"=>'CD-ROM'
        ];        
    }
    
    //INSERT TESTS
    public function testGoodInsert():void{
        $this->sth->method('fetch')->willReturn($this->sampleObject);
        $this->assertEquals($this->supportTypeService->selectById(1)->Name,"CD-ROM");        
    }
    
    public function testBadInsert():void{
        $this->expectException(ServiceException::class);
        $this->sth->method('fetch')->willReturn($this->sampleObject);
        $supportType = new SupportType(null,'CD-ROM');
        $this->supportTypeService->insert($supportType);
    }

    
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->sth->method('fetch')->willReturn($this->sampleObject);
        $this->assertEquals("CD-ROM",$this->supportTypeService->selectById(1)->Name);
    }
    
    public function testBadSelectById(): void
    {
        $this->expectException(ServiceException::class);
        $this->sth->method('fetch')->willReturn(null);
        $this->supportTypeService->selectById(2);
    }
    
    public function testBadSelectByName(): void
    {
        $this->expectException(ServiceException::class);
        $this->sth->method('fetch')->willReturn(null);
        $this->supportTypeService->selectByName("CD-ROM");
    }
    
    //UPDATE TESTS
    public function testBadUpdate():void{
        $this->expectException(ServiceException::class);
        $supportType = new SupportType(1,"FLOPPY");
        
        $this->sth->method('fetch')->willReturn(null);
        $this->supportTypeService->update($supportType);
    }
    
    //DELETE TESTS
    public function testBadDelete():void{
        $this->expectException(ServiceException::class);
        
        $this->sth->method('fetch')->willReturn(null);
        
        $this->supportTypeService->delete(5);
    }   
    

    // REMOVE USER FROM METHODS
}