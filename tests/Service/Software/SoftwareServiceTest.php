<?php

declare(strict_types=1);

namespace App\Test\Service;

use PHPUnit\Framework\TestCase;
use PDO;
use PDOStatement;
use App\Exception\ServiceException;

use App\Model\Software\Software;
use App\Model\Software\SoftwareType;
use App\Model\Software\SupportType;
use App\Model\Computer\Os;

use App\Repository\Software\SoftwareRepository;
use App\Repository\Software\SoftwareTypeRepository;
use App\Repository\Software\SupportTypeRepository;
use App\Repository\Computer\OsRepository;
use App\Service\Software\SoftwareService;

final class SoftwareServiceTest extends TestCase
{
    public SoftwareService $softwareService;
    public SoftwareTypeRepository $softwareTypeRepository;
    public SupportTypeRepository $supportTypeRepository;
    public OsRepository $osRepository;

    public function setUp(): void
    {        
        $this->pdo = $this->createMock(PDO::class);
        $this->sth = $this->createMock(PDOStatement::class);
        $this->pdo->method('prepare')->willReturn($this->sth);
        $this->sth->method('execute')->willReturn(true);

        $this->softwareTypeRepository = new SoftwareTypeRepository($this->pdo);
        $this->supportTypeRepository = new SupportTypeRepository($this->pdo);
        $this->osRepository = new OsRepository($this->pdo);
        $this->softwareRepository = new SoftwareRepository(
            $this->pdo, 
            $this->softwareTypeRepository, 
            $this->supportTypeRepository, 
            $this->osRepository
        );

        $this->softwareService = new SoftwareService($this->softwareRepository);        

        $this->sampleObject = new Software(
            "objID",
            null,
            null,
            null,
            "1",
            null,
            'Paint',
            new Os(1, 'Windows', null),
            new SoftwareType(1, 'Office'),
            new SupportType(1, 'Support')
        );    
        
        $this->sampleObjectRaw = [
            'ObjectID' => 'objID',
            'Title' => null,
            'OsID' => 1,
            'SoftwareTypeID' => 1,
            'SupportTypeID' => 1,
            'Note' => null,
            'Url' => null,
            'Tag' => 'Paint',
            'Active' => '1',
            'Erased' => null
        ];        
    }
    
    //INSERT TESTS
    public function testGoodInsert():void{
        $this->sth->method('fetch')->willReturn($this->sampleObjectRaw);
        $this->assertEquals($this->softwareService->selectById("objID")->Title,"Paint");        
    }
    
    public function testBadInsert():void{
        $this->expectException(ServiceException::class);
        $this->sth->method('fetch')->willReturn($this->sampleObjectRaw);
        $this->softwareService->insert($this->sampleObject);
    }

    /*
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->sth->method('fetch')->willReturn($this->sampleObject);
        $this->assertEquals("Office",$this->softwareService->selectById(1)->Name);
    }
    
    public function testBadSelectById(): void
    {
        $this->expectException(ServiceException::class);
        $this->sth->method('fetch')->willReturn(null);
        $this->softwareService->selectById(2);
    }
    
    public function testBadSelectByName(): void
    {
        $this->expectException(ServiceException::class);
        $this->sth->method('fetch')->willReturn(null);
        $this->softwareService->selectByName("WRONG");
    }
    
    //UPDATE TESTS
    public function testBadUpdate():void{
        $this->expectException(ServiceException::class);
        $softwareType = new Software(1,"Office");
        
        $this->sth->method('fetch')->willReturn(null);
        $this->softwareService->update($softwareType);
    }
    
    //DELETE TESTS
    public function testBadDelete():void{
        $this->expectException(ServiceException::class);
        
        $this->sth->method('fetch')->willReturn(null);
        
        $this->softwareService->delete(5);
    }   
    */
}