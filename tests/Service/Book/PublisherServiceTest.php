<?php

declare(strict_types=1);

namespace App\Test\Service\Book;

use App\Exception\ServiceException;
use App\Model\Book\Publisher;
use App\Repository\Book\PublisherRepository;
use App\Service\Book\PublisherService;
use App\Test\Service\BaseServiceTest;
use PHPUnit\Framework\TestCase;
use PDO;
use PDOStatement;

final class PublisherServiceTest extends BaseServiceTest
{
    public PublisherService $publisherService;
    
    public function setUp(): void
    {
        $this->publisherService = new PublisherService(new PublisherRepository($this->pdo));

        $this->sampleObject = [
            "id"=>1,
            "name"=>'Mondadori'
        ];        
    }
    
    //INSERT TESTS
    public function testGoodInsert():void{
        $this->sth->method('fetch')->willReturn($this->sampleObject);
        $this->assertEquals($this->publisherService->selectById(1)->name,"Mondadori");
    }
        
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->sth->method('fetch')->willReturn($this->sampleObject);
        $this->assertEquals("Mondadori",$this->publisherService->selectById(1)->name);
    }
    
    public function testBadSelectById(): void
    {
        $this->expectException(ServiceException::class);
        $this->sth->method('fetch')->willReturn(null);
        $this->publisherService->selectById(2);
    }
    
    public function testBadSelectByName(): void
    {
        $this->expectException(ServiceException::class);
        $this->sth->method('fetch')->willReturn(null);
        $this->publisherService->selectByName("WRONG NAME");
    }
    
    //UPDATE TESTS
    public function testBadUpdate():void{
        $this->expectException(ServiceException::class);
        $publisher = new Publisher("WRONG PUBLISHER",15);
        
        $this->sth->method('fetch')->willReturn(null);
        $this->publisherService->update($publisher);
    }
    
    //DELETE TESTS
    public function testBadDelete():void{
        $this->expectException(ServiceException::class);
        
        $this->sth->method('fetch')->willReturn(null);
        
        $this->publisherService->delete(5);
    }   
}