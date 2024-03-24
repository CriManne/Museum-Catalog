<?php

declare(strict_types=1);

namespace App\Test\Service\Book;

use App\Exception\ServiceException;
use App\Model\Book\Author;
use App\Repository\Book\AuthorRepository;
use App\Service\Book\AuthorService;
use App\Test\Service\BaseServiceTest;
use PHPUnit\Framework\TestCase;
use PDO;
use PDOStatement;

final class AuthorServiceTest extends BaseServiceTest
{
    public AuthorService $authorService;
    
    public function setUp(): void
    {
        $this->authorService = new AuthorService(new AuthorRepository($this->pdo));

        $this->sampleObject = [
            "id"=>1,
            "firstname"=>'Mario',
            "lastname"=>"Rossi"
        ];        
    }
    
    //INSERT TESTS
    public function testGoodInsert():void{
        $this->sth->method('fetch')->willReturn($this->sampleObject);
        $this->assertEquals($this->authorService->findById(1)->firstname,"Mario");        
    }
        
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->sth->method('fetch')->willReturn($this->sampleObject);
        $this->assertEquals("Mario",$this->authorService->findById(1)->firstname);
    }
    
    public function testBadSelectById(): void
    {
        $this->expectException(ServiceException::class);
        $this->sth->method('fetch')->willReturn(null);
        $this->authorService->findById(2);
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