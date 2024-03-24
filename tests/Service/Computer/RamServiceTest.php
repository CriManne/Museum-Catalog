<?php

declare(strict_types=1);

namespace App\Test\Service\Computer;

use App\Exception\ServiceException;
use App\Model\Computer\Ram;
use App\Repository\Computer\RamRepository;
use App\Service\Computer\RamService;
use App\Test\Service\BaseServiceTest;
use PHPUnit\Framework\TestCase;
use PDO;
use PDOStatement;

final class RamServiceTest extends BaseServiceTest
{
    public RamService $ramService;
    
    public function setUp(): void
    {        
        $this->ramService = new RamService(new RamRepository($this->pdo));

        $this->sampleObject = [
            "id"=>1,
            "modelName"=>'Ram 1.0',
            "size"=>"512KB"
        ];        
    }
    
    //INSERT TESTS
    public function testGoodInsert():void{
        $this->sth->method('fetch')->willReturn($this->sampleObject);
        $this->assertEquals($this->ramService->selectById(1)->modelName,"Ram 1.0");
    }
    
    public function testBadInsert():void{
        $this->expectException(ServiceException::class);
        $this->sth->method('fetch')->willReturn($this->sampleObject);
        $ram = new Ram('Ram 1.0','512KB',1);
        $this->ramService->insert($ram);
    }

    
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->sth->method('fetch')->willReturn($this->sampleObject);
        $this->assertEquals("Ram 1.0",$this->ramService->selectById(1)->modelName);
    }
    
    public function testBadSelectById(): void
    {
        $this->expectException(ServiceException::class);
        $this->sth->method('fetch')->willReturn(null);
        $this->ramService->selectById(2);
    }
    
    public function testBadSelectByName(): void
    {
        $this->expectException(ServiceException::class);
        $this->sth->method('fetch')->willReturn(null);
        $this->ramService->selectByName("WRONG NAME");
    }
    
    //UPDATE TESTS
    public function testBadUpdate():void{
        $this->expectException(ServiceException::class);
        $ram = new Ram("Ram 2.5","512KB",1);
        
        $this->sth->method('fetch')->willReturn(null);
        $this->ramService->update($ram);
    }
    
    //DELETE TESTS
    public function testBadDelete():void{
        $this->expectException(ServiceException::class);
        
        $this->sth->method('fetch')->willReturn(null);
        
        $this->ramService->delete(5);
    }   
}