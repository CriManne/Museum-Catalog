<?php

declare(strict_types=1);

namespace App\Test\Service\Computer;

use App\Exception\ServiceException;
use App\Model\Computer\Os;
use App\Repository\Computer\OsRepository;
use App\Service\Computer\OsService;
use App\Test\Service\BaseServiceTest;
use PHPUnit\Framework\TestCase;
use PDO;
use PDOStatement;

final class OsServiceTest extends BaseServiceTest
{
    public OsService $osService;
    
    public function setUp(): void
    {
        $this->osService = new OsService(new OsRepository($this->pdo));

        $this->sampleObject = [
            "id"=>1,
            "name"=>'Windows'
        ];        
    }
    
    //INSERT TESTS
    public function testGoodInsert():void{
        $this->sth->method('fetch')->willReturn($this->sampleObject);
        $this->assertEquals($this->osService->findById(1)->name,"Windows");
    }
    
    public function testBadInsert():void{
        $this->expectException(ServiceException::class);
        $this->sth->method('fetch')->willReturn($this->sampleObject);
        $os = new Os('Windows');
        $this->osService->save($os);
    }

    
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->sth->method('fetch')->willReturn($this->sampleObject);
        $this->assertEquals("Windows",$this->osService->findById(1)->name);
    }
    
    public function testBadSelectById(): void
    {
        $this->expectException(ServiceException::class);
        $this->sth->method('fetch')->willReturn(null);
        $this->osService->findById(2);
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
        $os = new Os("Linux",1);
        
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