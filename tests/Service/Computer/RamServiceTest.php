<?php

declare(strict_types=1);

namespace App\Test\Service\Computer;

use App\Exception\ServiceException;
use App\Models\Computer\Ram;
use App\Repository\Computer\RamRepository;
use App\Service\Computer\RamService;
use App\Test\Service\BaseServiceTest;
use PHPUnit\Framework\TestCase;
use PDO;
use PDOStatement;

final class RamServiceTest extends BaseServiceTest
{
    public RamService $ramService;
    public RamRepository $ramRepository;
    
    public function setUp(): void
    {
        $this->ramRepository = $this->createMock(RamRepository::class);
        $this->ramService = new RamService($this->ramRepository);

        $this->sampleObject = new Ram(
            modelName: 'Ram 1.0',
            size: '512KB',
            id: 1
        );
    }
    
    //INSERT TESTS
    public function testGoodInsert():void{
        $this->expectNotToPerformAssertions();
        $this->ramRepository->method('findFirst')->willReturn(null);
        $ram = new Ram('Ram 1.0','512KB',1);
        $this->ramService->save($ram);
    }
    
    public function testBadInsert():void{
        $this->expectException(ServiceException::class);
        $this->ramRepository->method('findFirst')->willReturn($this->sampleObject);
        $ram = new Ram('Ram 1.0','512KB',1);
        $this->ramService->save($ram);
    }

    
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->ramRepository->method('findById')->willReturn($this->sampleObject);
        $this->assertEquals("Ram 1.0",$this->ramService->findById(1)->modelName);
    }
    
    public function testBadSelectById(): void
    {
        $this->expectException(ServiceException::class);
        $this->ramRepository->method('findById')->willReturn(null);
        $this->ramService->findById(2);
    }
    
    //UPDATE TESTS
    public function testBadUpdate():void{
        $this->expectException(ServiceException::class);
        $ram = new Ram("Ram 2.5","512KB",1);

        $this->ramRepository->method('findById')->willReturn(null);
        $this->ramService->update($ram);
    }
    
    //DELETE TESTS
    public function testBadDelete():void{
        $this->expectException(ServiceException::class);

        $this->ramRepository->method('findById')->willReturn(null);
        
        $this->ramService->delete(5);
    }   
}