<?php
declare(strict_types=1);

namespace App\Test\Service\Computer;

use App\Test\Service\BaseServiceTest;
use PHPUnit\Framework\TestCase;
use App\Exception\ServiceException;

use App\Model\Computer\Os;
use App\Model\Computer\Ram;
use App\Model\Computer\Cpu;
use App\Model\Computer\Computer;

use App\Repository\Computer\ComputerRepository;
use App\Service\Computer\ComputerService;

final class ComputerServiceTest extends BaseServiceTest
{
    public ComputerRepository $computerRepository;
    public ComputerService $computerService;
    

    public function setUp(): void
    {                
        $this->computerRepository = $this->createMock(ComputerRepository::class);

        $this->computerService = new ComputerService($this->computerRepository);        

        $this->sampleObject = new Computer(
            "objID",
            'Computer 1',
            2005,
            "1TB",
            new Cpu('Cpu 1.0','2GHZ',1),
            new Ram('Ram 1.0','64GB',1),
            new Os('Windows',1),
            null,
            null,
            null,
        );
        
        $this->sampleObjectRaw = [
            'modelName' => 'Computer 1',
            'year' => 2005,
            'hddSize' => "1TB",
            'osId' => 1,
            'ramId' => 1,
            'cpuId' => 1,
            'objectId' => 'objID',
            'note' => null,
            'url' => null,
            'tag' => null,
            'active' => '1'
        ];        
    }
    
    
    //INSERT TESTS
    
    public function testBadInsert():void{
        $this->expectException(ServiceException::class);
        $this->computerRepository->method('findByModelName')->willReturn($this->sampleObject);
        $this->computerService->save($this->sampleObject);
    }
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->computerRepository->method('findById')->willReturn($this->sampleObject);
        $this->assertEquals("Computer 1",$this->computerService->findById("ObjID")->modelName);
    }
    
    public function testBadSelectById(): void
    {
        $this->expectException(ServiceException::class);
        $this->computerRepository->method('findById')->willReturn(null);
        $this->computerService->findById("ObjID25");
    }
    
    public function testBadSelectByName(): void
    {
        $this->expectException(ServiceException::class);
        $this->computerRepository->method('findByModelName')->willReturn(null);
        $this->computerService->findByModelName("WRONG");
    }
    
    //UPDATE TESTS
    public function testBadUpdate():void{
        $this->expectException(ServiceException::class);                
        $this->computerRepository->method('findById')->willReturn(null);
        $this->computerService->update($this->sampleObject);
    }
    
    //DELETE TESTS
    public function testBadDelete():void{
        $this->expectException(ServiceException::class);
        
        $this->computerRepository->method('findById')->willReturn(null);
        
        $this->computerService->delete("ObjID99");
    }       
}
