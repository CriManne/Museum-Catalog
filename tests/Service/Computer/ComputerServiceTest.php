<?php
declare(strict_types=1);

namespace App\Test\Service;

use PHPUnit\Framework\TestCase;
use App\Exception\ServiceException;

use App\Model\Computer\Os;
use App\Model\Computer\Ram;
use App\Model\Computer\Cpu;
use App\Model\Computer\Computer;

use App\Repository\Computer\ComputerRepository;
use App\Service\Computer\ComputerService;

final class ComputerServiceTest extends TestCase
{
    public ComputerRepository $computerRepository;
    public ComputerService $computerService;
    

    public function setUp(): void
    {                
        $this->computerRepository = $this->createMock(ComputerRepository::class);

        $this->computerService = new ComputerService($this->computerRepository);        

        $this->sampleObject = new Computer(
            "objID",
            null,
            null,
            null,
            'Computer 1',
            2005,
            "1TB",            
            new Cpu('Cpu 1.0','2GHZ',1),
            new Ram('Ram 1.0','64GB',1),
            new Os('Windows',1),
        );    
        
        $this->sampleObjectRaw = [
            'ModelName' => 'Computer 1',
            'Year' => 2005,
            'HDDSize' => "1TB",
            'OsID' => 1,
            'RamID' => 1,
            'CpuID' => 1,
            'ObjectID' => 'objID',
            'Note' => null,
            'Url' => null,
            'Tag' => null,
            'Active' => '1',
            'Erased' => null,
        ];        
    }
    
    
    //INSERT TESTS
    
    public function testBadInsert():void{
        $this->expectException(ServiceException::class);
        $this->computerRepository->method('selectByModelName')->willReturn($this->sampleObject);
        $this->computerService->insert($this->sampleObject);
    }
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->computerRepository->method('selectById')->willReturn($this->sampleObject);
        $this->assertEquals("Computer 1",$this->computerService->selectById("ObjID")->ModelName);
    }
    
    public function testBadSelectById(): void
    {
        $this->expectException(ServiceException::class);
        $this->computerRepository->method('selectById')->willReturn(null);
        $this->computerService->selectById("ObjID25");
    }
    
    public function testBadSelectByName(): void
    {
        $this->expectException(ServiceException::class);
        $this->computerRepository->method('selectByModelName')->willReturn(null);
        $this->computerService->selectByModelName("WRONG");
    }
    
    //UPDATE TESTS
    public function testBadUpdate():void{
        $this->expectException(ServiceException::class);                
        $this->computerRepository->method('selectById')->willReturn(null);
        $this->computerService->update($this->sampleObject);
    }
    
    //DELETE TESTS
    public function testBadDelete():void{
        $this->expectException(ServiceException::class);
        
        $this->computerRepository->method('selectById')->willReturn(null);
        
        $this->computerService->delete("ObjID99");
    }       
}
