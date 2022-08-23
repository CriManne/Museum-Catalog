<?php
declare(strict_types=1);

namespace App\Test\Service;

use PHPUnit\Framework\TestCase;
use App\Exception\ServiceException;

use App\Model\Software\Software;
use App\Model\Software\SoftwareType;
use App\Model\Software\SupportType;
use App\Model\Computer\Os;

use App\Repository\Software\SoftwareRepository;
use App\Service\Software\SoftwareService;

final class SoftwareServiceTest extends TestCase
{
    public SoftwareRepository $softwareRepository;
    public SoftwareService $softwareService;
    

    public function setUp(): void
    {                
        $this->softwareRepository = $this->createMock(SoftwareRepository::class);

        $this->softwareService = new SoftwareService($this->softwareRepository);        

        $this->sampleObject = new Software(
            "objID",
            null,
            null,
            null,
            "1",
            null,
            'Paint',
            new Os(1, 'Windows'),
            new SoftwareType(1, 'Office'),
            new SupportType(1, 'Support')
        );    
        
        $this->sampleObjectRaw = [
            'Title' => 'Paint',
            'OsID' => 1,
            'SoftwareTypeID' => 1,
            'SupportTypeID' => 1,
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
        $this->softwareRepository->method('selectByTitle')->willReturn($this->sampleObject);
        $this->softwareService->insert($this->sampleObject);
    }
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->softwareRepository->method('selectById')->willReturn($this->sampleObject);
        $this->assertEquals("Paint",$this->softwareService->selectById("ObjID")->Title);
    }
    
    public function testBadSelectById(): void
    {
        $this->expectException(ServiceException::class);
        $this->softwareRepository->method('selectById')->willReturn(null);
        $this->softwareService->selectById("ObjID25");
    }
    
    public function testBadSelectByName(): void
    {
        $this->expectException(ServiceException::class);
        $this->softwareRepository->method('selectByTitle')->willReturn(null);
        $this->softwareService->selectByTitle("WRONG");
    }
    
    //UPDATE TESTS
    public function testBadUpdate():void{
        $this->expectException(ServiceException::class);                
        $this->softwareRepository->method('selectById')->willReturn(null);
        $this->softwareService->update($this->sampleObject);
    }
    
    //DELETE TESTS
    public function testBadDelete():void{
        $this->expectException(ServiceException::class);
        
        $this->softwareRepository->method('selectById')->willReturn(null);
        
        $this->softwareService->delete("ObjID99");
    }       
}
