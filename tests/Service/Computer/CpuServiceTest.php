<?php

declare(strict_types=1);

namespace App\Test\Service;

use App\Exception\ServiceException;
use App\Model\Computer\Cpu;
use App\Repository\Computer\CpuRepository;
use App\Service\Computer\CpuService;
use PHPUnit\Framework\TestCase;
use PDO;
use PDOStatement;

final class CpuServiceTest extends TestCase
{
    public CpuService $cpuService;
    
    public function setUp(): void
    {        
        $this->pdo = $this->createMock(PDO::class);
        $this->sth = $this->createMock(PDOStatement::class);
        $this->pdo->method('prepare')->willReturn($this->sth);
        $this->sth->method('execute')->willReturn(true);
        $this->cpuRepository = new CpuRepository($this->pdo);    
        $this->cpuService = new CpuService($this->cpuRepository);        

        $this->sampleObject = [
            "CpuID"=>1,
            "ModelName"=>'Cpu 1.0',
            "Speed"=>"4GHZ"
        ];        
    }
    
    //INSERT TESTS
    public function testGoodInsert():void{
        $this->sth->method('fetch')->willReturn($this->sampleObject);
        $this->assertEquals($this->cpuService->selectById(1)->ModelName,"Cpu 1.0");        
    }
    
    public function testBadInsert():void{
        $this->expectException(ServiceException::class);
        $this->sth->method('fetch')->willReturn($this->sampleObject);
        $cpu = new Cpu('Cpu 1.0','4GHZ',1);
        $this->cpuService->insert($cpu);
    }

    
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->sth->method('fetch')->willReturn($this->sampleObject);
        $this->assertEquals("Cpu 1.0",$this->cpuService->selectById(1)->ModelName);
    }
    
    public function testBadSelectById(): void
    {
        $this->expectException(ServiceException::class);
        $this->sth->method('fetch')->willReturn(null);
        $this->cpuService->selectById(2);
    }
    
    public function testBadSelectByName(): void
    {
        $this->expectException(ServiceException::class);
        $this->sth->method('fetch')->willReturn(null);
        $this->cpuService->selectByName("WRONG NAME");
    }
    
    //UPDATE TESTS
    public function testBadUpdate():void{
        $this->expectException(ServiceException::class);
        $cpu = new Cpu("Cpu 2.5","4GHZ",1);
        
        $this->sth->method('fetch')->willReturn(null);
        $this->cpuService->update($cpu);
    }
    
    //DELETE TESTS
    public function testBadDelete():void{
        $this->expectException(ServiceException::class);
        
        $this->sth->method('fetch')->willReturn(null);
        
        $this->cpuService->delete(5);
    }   
}