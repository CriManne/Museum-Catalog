<?php

declare(strict_types=1);

namespace App\Test\Service;

use App\Exception\ServiceException;
use App\Model\Computer\Os;
use App\Repository\Computer\OsRepository;
use App\Service\Computer\OsService;
use PHPUnit\Framework\TestCase;
use PDO;
use PDOStatement;

final class OsServiceTest extends TestCase
{
    public OsService $osService;
    
    public function setUp(): void
    {        
        $this->pdo = $this->createMock(PDO::class);
        $this->sth = $this->createMock(PDOStatement::class);
        $this->pdo->method('prepare')->willReturn($this->sth);
        $this->sth->method('execute')->willReturn(true);
        $this->osRepository = new OsRepository($this->pdo);    
        $this->osService = new OsService($this->osRepository);        

        $this->sampleObject = [
            "OsID"=>1,
            "Name"=>'Windows',
            "Erased"=>null
        ];        
    }
    
    //INSERT TESTS
    public function testGoodInsert():void{
        $this->sth->method('fetch')->willReturn($this->sampleObject);
        $this->assertEquals($this->osService->selectById(1)->Name,"Windows");        
    }
    
    public function testBadInsert():void{
        $this->expectException(ServiceException::class);
        $this->sth->method('fetch')->willReturn($this->sampleObject);
        $os = new Os(null,'Windows');
        $this->osService->insert($os);
    }

    
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->sth->method('fetch')->willReturn($this->sampleObject);
        $this->assertEquals("Windows",$this->osService->selectById(1)->Name);
    }
    
    public function testBadSelectById(): void
    {
        $this->expectException(ServiceException::class);
        $this->sth->method('fetch')->willReturn(null);
        $this->osService->selectById(2);
    }
    
    public function testBadSelectByName(): void
    {
        $this->expectException(ServiceException::class);
        $this->sth->method('fetch')->willReturn(null);
        $this->osService->selectByName("Windows");
    }
    
    //UPDATE TESTS
    public function testBadUpdate():void{
        $this->expectException(ServiceException::class);
        $os = new Os(1,"Linux");
        
        $this->sth->method('fetch')->willReturn(null);
        $this->osService->update($os);
    }
    
    //DELETE TESTS
    public function testBadDelete():void{
        $this->expectException(ServiceException::class);
        
        $this->sth->method('fetch')->willReturn(null);
        
        $this->osService->delete(5);
    }   
}