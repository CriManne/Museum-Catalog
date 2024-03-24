<?php
declare(strict_types=1);

namespace App\Test\Service\Magazine;

use App\Test\Service\BaseServiceTest;
use PHPUnit\Framework\TestCase;
use App\Exception\ServiceException;

use App\Model\Magazine\Magazine;
use App\Model\Book\Publisher;

use App\Repository\Magazine\MagazineRepository;
use App\Service\Magazine\MagazineService;

final class MagazineServiceTest extends BaseServiceTest
{
    public MagazineRepository $magazineRepository;
    public MagazineService $magazineService;
    

    public function setUp(): void
    {
        $this->magazineRepository = $this->createMock(MagazineRepository::class);

        $this->magazineService = new MagazineService($this->magazineRepository);

        $this->sampleObject = new Magazine(
            "objID",
            'Magazine title',
            2005,
            205,
            new Publisher('Publisher 1',1),
            null,
            null,
            null,
        );
        
        $this->sampleObjectRaw = [
            'title' => 'Magazine title',
            'year' => 2005,
            'magazineNumber' => 205,
            'publisherId' => 1,
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
        $this->magazineRepository->method('findByTitle')->willReturn($this->sampleObject);
        $this->magazineService->save($this->sampleObject);
    }
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->magazineRepository->method('findById')->willReturn($this->sampleObject);
        $this->assertEquals("Magazine title",$this->magazineService->findById("ObjID")->title);
    }
    
    public function testBadSelectById(): void
    {
        $this->expectException(ServiceException::class);
        $this->magazineRepository->method('findById')->willReturn(null);
        $this->magazineService->findById("ObjID25");
    }
    
    public function testBadSelectByName(): void
    {
        $this->expectException(ServiceException::class);
        $this->magazineRepository->method('findByTitle')->willReturn(null);
        $this->magazineService->findByTitle("WRONG");
    }
    
    //UPDATE TESTS
    public function testBadUpdate():void{
        $this->expectException(ServiceException::class);                
        $this->magazineRepository->method('findById')->willReturn(null);
        $this->magazineService->update($this->sampleObject);
    }
    
    //DELETE TESTS
    public function testBadDelete():void{
        $this->expectException(ServiceException::class);
        
        $this->magazineRepository->method('findById')->willReturn(null);
        
        $this->magazineService->delete("ObjID99");
    }       
}
