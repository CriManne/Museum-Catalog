<?php

declare(strict_types=1);

namespace App\Test\Service\Software;

use App\Exception\ServiceException;
use App\Model\Software\SupportType;
use App\Repository\Software\SupportTypeRepository;
use App\Service\Software\SupportTypeService;
use App\Test\Service\BaseServiceTest;
use PHPUnit\Framework\TestCase;
use PDO;
use PDOStatement;

final class SupportTypeServiceTest extends BaseServiceTest
{
    public SupportTypeService $supportTypeService;
    public SupportTypeRepository $supportTypeRepository;
    
    public function setUp(): void
    {        
        $this->supportTypeRepository = new SupportTypeRepository($this->pdo);
        $this->supportTypeService = new SupportTypeService($this->supportTypeRepository);        

        $this->sampleObject = [
            "id"=>1,
            "name"=>'CD-ROM'
        ];        
    }
    
    //INSERT TESTS    
    public function testBadInsert():void{
        $this->expectException(ServiceException::class);
        $this->sth->method('fetch')->willReturn($this->sampleObject);
        $supportType = new SupportType('CD-ROM');
        $this->supportTypeService->save($supportType);
    }

    
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->sth->method('fetch')->willReturn($this->sampleObject);
        $this->assertEquals("CD-ROM",$this->supportTypeService->findById(1)->name);
    }
    
    public function testBadSelectById(): void
    {
        $this->expectException(ServiceException::class);
        $this->sth->method('fetch')->willReturn(null);
        $this->supportTypeService->findById(2);
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
        $supportType = new SupportType("FLOPPY",1);
        
        $this->sth->method('fetch')->willReturn(null);
        $this->supportTypeService->update($supportType);
    }
    
    //DELETE TESTS
    public function testBadDelete():void{
        $this->expectException(ServiceException::class);
        
        $this->sth->method('fetch')->willReturn(null);
        
        $this->supportTypeService->delete(5);
    }   
}