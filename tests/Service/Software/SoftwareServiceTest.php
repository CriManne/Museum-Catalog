<?php
declare(strict_types=1);

namespace App\Test\Service\Software;

use App\Test\Service\BaseServiceTest;
use PHPUnit\Framework\TestCase;
use App\Exception\ServiceException;

use App\Model\Software\Software;
use App\Model\Software\SoftwareType;
use App\Model\Software\SupportType;
use App\Model\Computer\Os;

use App\Repository\Software\SoftwareRepository;
use App\Service\Software\SoftwareService;

final class SoftwareServiceTest extends BaseServiceTest
{
    public SoftwareRepository $softwareRepository;
    public SoftwareService $softwareService;
    

    public function setUp(): void
    {                
        $this->softwareRepository = $this->createMock(SoftwareRepository::class);

        $this->softwareService = new SoftwareService($this->softwareRepository);        

        $this->sampleObject = new Software(
            "objID",
            'Paint',
            new Os('Windows',1),
            new SoftwareType('Office',1),
            new SupportType('Support',1),
            null,
            null,
            null,
        );
        
        $this->sampleObjectRaw = [
            'title' => 'Paint',
            'osId' => 1,
            'softwareTypeId' => 1,
            'supportTypeId' => 1,
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
        $this->softwareRepository->method('findByTitle')->willReturn($this->sampleObject);
        $this->softwareService->insert($this->sampleObject);
    }
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->softwareRepository->method('findById')->willReturn($this->sampleObject);
        $this->assertEquals("Paint",$this->softwareService->findById("ObjID")->title);
    }
    
    public function testBadSelectById(): void
    {
        $this->expectException(ServiceException::class);
        $this->softwareRepository->method('findById')->willReturn(null);
        $this->softwareService->findById("ObjID25");
    }
    
    public function testBadSelectByName(): void
    {
        $this->expectException(ServiceException::class);
        $this->softwareRepository->method('findByTitle')->willReturn(null);
        $this->softwareService->findByTitle("WRONG");
    }
    
    //UPDATE TESTS
    public function testBadUpdate():void{
        $this->expectException(ServiceException::class);                
        $this->softwareRepository->method('findById')->willReturn(null);
        $this->softwareService->update($this->sampleObject);
    }
    
    //DELETE TESTS
    public function testBadDelete():void{
        $this->expectException(ServiceException::class);
        
        $this->softwareRepository->method('findById')->willReturn(null);
        
        $this->softwareService->delete("ObjID99");
    }       
}
