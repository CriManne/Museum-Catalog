<?php
declare(strict_types=1);

namespace App\Test\Service;

use PHPUnit\Framework\TestCase;
use App\Exception\ServiceException;

use App\Model\Book\Book;
use App\Model\Book\Author;
use App\Model\Book\BookAuthor;
use App\Model\Book\Publisher;

use App\Repository\Book\BookRepository;
use App\Service\Book\BookService;

final class BookServiceTest extends TestCase
{
    public BookRepository $bookRepository;
    public BookService $bookService;
    

    public function setUp(): void
    {                
        $this->bookRepository = $this->createMock(BookRepository::class);

        $this->bookService = new BookService($this->bookRepository);        

        $this->sampleObject = new Book(
            "objID",
            null,
            null,
            null,
            "1",
            null,
            '1984',
            new Publisher(1,"Mondadori"),
            1945,
            "ALSKDI82SB",
            245,
            [
                new Author(1,"George","Orwell")
            ]
        );    
        
        $this->sampleObjectRaw = [
            'Title' => '1984',
            'PublisherID' => 1,
            'Year' => 1945,
            'ISBN' => 'ALSKDI82SB',
            'Pages' => 245,            
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
        $this->bookRepository->method('selectByTitle')->willReturn($this->sampleObject);
        $this->bookService->insert($this->sampleObject);
    }
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->bookRepository->method('selectById')->willReturn($this->sampleObject);
        $this->assertEquals("1984",$this->bookService->selectById("ObjID")->Title);
    }
    
    public function testBadSelectById(): void
    {
        $this->expectException(ServiceException::class);
        $this->bookRepository->method('selectById')->willReturn(null);
        $this->bookService->selectById("ObjID25");
    }
    
    public function testBadSelectByTitle(): void
    {
        $this->expectException(ServiceException::class);
        $this->bookRepository->method('selectByTitle')->willReturn(null);
        $this->bookService->selectByTitle("WRONG");
    }
    
    //UPDATE TESTS
    public function testBadUpdate():void{
        $this->expectException(ServiceException::class);                
        $this->bookRepository->method('selectById')->willReturn(null);
        $this->bookService->update($this->sampleObject);
    }
    
    //DELETE TESTS
    public function testBadDelete():void{
        $this->expectException(ServiceException::class);
        
        $this->bookRepository->method('selectById')->willReturn(null);
        
        $this->bookService->delete("ObjID99");
    }       
}
