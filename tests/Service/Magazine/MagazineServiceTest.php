<?php
declare(strict_types=1);

namespace App\Test\Service;

use PHPUnit\Framework\TestCase;
use App\Exception\ServiceException;

use App\Model\Magazine\Magazine;
use App\Model\Book\Publisher;

use App\Repository\Magazine\MagazineRepository;
use App\Service\Magazine\MagazineService;

final class MagazineServiceTest extends TestCase
{
    public MagazineRepository $magazineRepository;
    public MagazineService $magazineService;
    

    public function setUp(): void
    {                
        $this->magazineRepository = $this->createMock(MagazineRepository::class);

        $this->magazineService = new MagazineService($this->magazineRepository);        

        $this->sampleObject = new Magazine(
            "objID",
            null,
            null,
            null,
            "1",
            null,
            'Magazine Title',   
            2005,
            205,
            new Publisher(1, 'Publisher 1')
        );    
        
        $this->sampleObjectRaw = [
            'Title' => 'Magazine Title',
            'Year' => 2005,
            'MagazineNumber' => 205,
            'PublisherID' => 1,
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
        $this->magazineRepository->method('selectByTitle')->willReturn($this->sampleObject);
        $this->magazineService->insert($this->sampleObject);
    }
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->magazineRepository->method('selectById')->willReturn($this->sampleObject);
        $this->assertEquals("Magazine Title",$this->magazineService->selectById("ObjID")->Title);
    }
    
    public function testBadSelectById(): void
    {
        $this->expectException(ServiceException::class);
        $this->magazineRepository->method('selectById')->willReturn(null);
        $this->magazineService->selectById("ObjID25");
    }
    
    public function testBadSelectByName(): void
    {
        $this->expectException(ServiceException::class);
        $this->magazineRepository->method('selectByTitle')->willReturn(null);
        $this->magazineService->selectByTitle("WRONG");
    }
    
    //UPDATE TESTS
    public function testBadUpdate():void{
        $this->expectException(ServiceException::class);                
        $this->magazineRepository->method('selectById')->willReturn(null);
        $this->magazineService->update($this->sampleObject);
    }
    
    //DELETE TESTS
    public function testBadDelete():void{
        $this->expectException(ServiceException::class);
        
        $this->magazineRepository->method('selectById')->willReturn(null);
        
        $this->magazineService->delete("ObjID99");
    }       
}
