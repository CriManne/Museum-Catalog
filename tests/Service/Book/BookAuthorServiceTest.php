<?php

declare(strict_types=1);

namespace App\Test\Service;

use App\Exception\ServiceException;
use App\Model\Book\BookAuthor;
use App\Repository\Book\BookAuthorRepository;
use App\Service\Book\BookAuthorService;
use PHPUnit\Framework\TestCase;
use PDO;
use PDOStatement;

final class BookAuthorServiceTest extends TestCase
{
    public BookAuthorService $bookAuthorService;
    
    public function setUp(): void
    {        
        $this->pdo = $this->createMock(PDO::class);
        $this->sth = $this->createMock(PDOStatement::class);
        $this->pdo->method('prepare')->willReturn($this->sth);
        $this->sth->method('execute')->willReturn(true);
        $this->bookAuthorRepository = new BookAuthorRepository($this->pdo);    
        $this->bookAuthorService = new BookAuthorService($this->bookAuthorRepository);        

        $this->sampleObject = [
            "BookID"=>'BOOK1',
            "AuthorID"=>1
        ];        
    }
    
    //INSERT TESTS
    public function testGoodInsert():void{
        $this->sth->method('fetch')->willReturn($this->sampleObject);
        $this->assertEquals($this->bookAuthorService->selectById("BOOK1",1)->BookID,"BOOK1");        
    }
        
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->sth->method('fetch')->willReturn($this->sampleObject);
        $this->assertEquals(1,$this->bookAuthorService->selectById("BOOK1",1)->AuthorID);
    }
    
    public function testBadSelectById(): void
    {
        $this->expectException(ServiceException::class);
        $this->sth->method('fetch')->willReturn(null);
        $this->bookAuthorService->selectById("TEST",1);
    }
    
    public function testBadSelectByBookID(): void
    {
        $this->expectException(ServiceException::class);
        $this->sth->method('fetch')->willReturn(null);
        $this->bookAuthorService->selectByBookID("WRONG NAME");
    }
        
    //DELETE TESTS
    public function testBadDelete():void{
        $this->expectException(ServiceException::class);
        
        $this->sth->method('fetch')->willReturn(null);
        
        $this->bookAuthorService->delete("WRONG BOOK",1);
    }   
}