<?php

declare(strict_types=1);

namespace App\Test\Service;

use App\Exception\ServiceException;
use App\Model\Book\Author;
use App\Repository\Book\AuthorRepository;
use App\Service\Book\AuthorService;
use PHPUnit\Framework\TestCase;
use PDO;
use PDOStatement;

final class AuthorServiceTest extends TestCase
{
    public AuthorService $authorService;
    
    public function setUp(): void
    {        
        $this->pdo = $this->createMock(PDO::class);
        $this->sth = $this->createMock(PDOStatement::class);
        $this->pdo->method('prepare')->willReturn($this->sth);
        $this->sth->method('execute')->willReturn(true);
        $this->authorRepository = new AuthorRepository($this->pdo);    
        $this->authorService = new AuthorService($this->authorRepository);        

        $this->sampleObject = [
            "AuthorID"=>1,
            "firstname"=>'Mario',
            "lastname"=>"Rossi",
            "Erased"=>null
        ];        
    }
    
    //INSERT TESTS
    public function testGoodInsert():void{
        $this->sth->method('fetch')->willReturn($this->sampleObject);
        $this->assertEquals($this->authorService->selectById(1)->firstname,"Mario");        
    }
        
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->sth->method('fetch')->willReturn($this->sampleObject);
        $this->assertEquals("Mario",$this->authorService->selectById(1)->firstname);
    }
    
    public function testBadSelectById(): void
    {
        $this->expectException(ServiceException::class);
        $this->sth->method('fetch')->willReturn(null);
        $this->authorService->selectById(2);
    }
    
    public function testBadSelectByKey(): void
    {
        $this->expectException(ServiceException::class);
        $this->sth->method('fetch')->willReturn(null);
        $this->authorService->selectByKey("WRONG NAME");
    }
    
    //UPDATE TESTS
    public function testBadUpdate():void{
        $this->expectException(ServiceException::class);
        $author = new Author("WRONG AUTHOR","Rossi",1);
        
        $this->sth->method('fetch')->willReturn(null);
        $this->authorService->update($author);
    }
    
    //DELETE TESTS
    public function testBadDelete():void{
        $this->expectException(ServiceException::class);
        
        $this->sth->method('fetch')->willReturn(null);
        
        $this->authorService->delete(5);
    }   
}