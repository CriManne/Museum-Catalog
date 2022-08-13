<?php

declare(strict_types=1);

namespace App\Test\Service;

use App\Exception\ServiceException;
use App\Model\Computer\Ram;
use App\Repository\Computer\RamRepository;
use App\Service\Computer\RamService;
use PHPUnit\Framework\TestCase;
use PDO;
use PDOStatement;

final class RamServiceTest extends TestCase
{
    public RamService $ramService;
    
    public function setUp(): void
    {        
        $this->pdo = $this->createMock(PDO::class);
        $this->sth = $this->createMock(PDOStatement::class);
        $this->pdo->method('prepare')->willReturn($this->sth);
        $this->sth->method('execute')->willReturn(true);
        $this->ramRepository = new RamRepository($this->pdo);    
        $this->ramService = new RamService($this->ramRepository);        

        $this->sampleObject = [
            "RamID"=>1,
            "ModelName"=>'Ram 1.0',
            "Size"=>"512KB",
            "Erased"=>null
        ];        
    }
    
    //INSERT TESTS
    public function testGoodInsert():void{
        $this->sth->method('fetch')->willReturn($this->sampleObject);
        $this->assertEquals($this->ramService->selectById(1)->ModelName,"Ram 1.0");        
    }
    
    public function testBadInsert():void{
        $this->expectException(ServiceException::class);
        $this->sth->method('fetch')->willReturn($this->sampleObject);
        $ram = new Ram(1,'Ram 1.0','512KB');
        $this->ramService->insert($ram);
    }

    
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->sth->method('fetch')->willReturn($this->sampleObject);
        $this->assertEquals("Ram 1.0",$this->ramService->selectById(1)->ModelName);
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
        $ram = new Ram(1,"Ram 2.5","512KB");
        
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