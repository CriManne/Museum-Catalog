<?php
declare(strict_types=1);

namespace App\Test\Service;

use PHPUnit\Framework\TestCase;
use App\Exception\ServiceException;

use App\Model\Response\GenericObjectResponse;
use App\Repository\GenericObjectRepository;
use App\Service\GenericObjectService;

final class GenericObjectServiceTest extends TestCase
{
    public GenericObjectRepository $genericObjectRepository;
    public GenericObjectService $genericObjectService;
    

    public function setUp(): void
    {                
        $this->genericObjectRepository = $this->createMock(GenericObjectRepository::class);

        $this->genericObjectService = new GenericObjectService($this->genericObjectRepository);        

        $this->sampleObject = new GenericObjectResponse(
            "objID",
            "Title",
            [
                "Desc1"=>"Val1",
                "Desc2"=>"Val2"
            ],
            null,
            null,
            null
        );           
             
    }
    
    
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->genericObjectRepository->method('selectById')->willReturn($this->sampleObject);
        $this->assertEquals("Title",$this->genericObjectService->selectById("ObjID")->Title);
    }
    
    public function testBadSelectById(): void
    {
        $this->expectException(ServiceException::class);
        $this->genericObjectRepository->method('selectById')->willReturn(null);
        $this->genericObjectService->selectById("ObjID25");
    } 
}
