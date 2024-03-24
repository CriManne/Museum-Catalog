<?php

declare(strict_types=1);

namespace App\Test\Service\Computer;

use App\Exception\ServiceException;
use App\Model\Computer\Cpu;
use App\Repository\Computer\CpuRepository;
use App\Service\Computer\CpuService;
use App\Test\Service\BaseServiceTest;
use PHPUnit\Framework\TestCase;
use PDO;
use PDOStatement;

final class CpuServiceTest extends BaseServiceTest
{
    public CpuService $cpuService;
    
    public function setUp(): void
    {        
        $this->cpuService = new CpuService(new CpuRepository($this->pdo));

        $this->sampleObject = [
            "id"=>1,
            "modelName"=>'Cpu 1.0',
            "speed"=>"4GHZ"
        ];        
    }
    
    //INSERT TESTS
    public function testGoodInsert():void{
        $this->sth->method('fetch')->willReturn($this->sampleObject);
        $this->assertEquals($this->cpuService->findById(1)->modelName,"Cpu 1.0");
    }
    
    public function testBadInsert():void{
        $this->expectException(ServiceException::class);
        $this->sth->method('fetch')->willReturn($this->sampleObject);
        $cpu = new Cpu('Cpu 1.0','4GHZ',1);
        $this->cpuService->save($cpu);
    }

    
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->sth->method('fetch')->willReturn($this->sampleObject);
        $this->assertEquals("Cpu 1.0",$this->cpuService->findById(1)->modelName);
    }
    
    public function testBadSelectById(): void
    {
        $this->expectException(ServiceException::class);
        $this->sth->method('fetch')->willReturn(null);
        $this->cpuService->findById(2);
    }
    
    public function testBadSelectByName(): void
    {
        $this->expectException(ServiceException::class);
        $this->sth->method('fetch')->willReturn(null);
        $this->cpuService->findByName("WRONG NAME");
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