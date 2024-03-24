<?php
declare(strict_types=1);

namespace App\Test\Service\Book;

use App\Test\Service\BaseServiceTest;
use PHPUnit\Framework\TestCase;
use App\Exception\ServiceException;

use App\Model\Book\Book;
use App\Model\Book\Author;
use App\Model\Book\BookAuthor;
use App\Model\Book\Publisher;

use App\Repository\Book\BookRepository;
use App\Service\Book\BookService;

final class BookServiceTest extends BaseServiceTest
{
    public BookRepository $bookRepository;
    public BookService $bookService;
    

    public function setUp(): void
    {                
        $this->bookRepository = $this->createMock(BookRepository::class);

        $this->bookService = new BookService($this->bookRepository);        

        $this->sampleObject = new Book(
            "objID",
            '1984',
            new Publisher("Mondadori",1),
            1945,
            [
                new Author("George","Orwell",1)
            ],
            null,
            null,
            null,
            "ALSKDI82SB",
            245,
        );
        
        $this->sampleObjectRaw = [
            'title' => '1984',
            'publisherId' => 1,
            'year' => 1945,
            'isbn' => 'ALSKDI82SB',
            'pages' => 245,
            'objectId' => 'objID',
            'note' => null,
            'url' => null,
            'tag' => null,
            'active' => '1'
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
        $this->bookRepository->method('findById')->willReturn($this->sampleObject);
        $this->assertEquals("1984",$this->bookService->findById("ObjID")->title);
    }
    
    public function testBadSelectById(): void
    {
        $this->expectException(ServiceException::class);
        $this->bookRepository->method('findById')->willReturn(null);
        $this->bookService->findById("ObjID25");
    }
    
    public function testBadSelectBytitle(): void
    {
        $this->expectException(ServiceException::class);
        $this->bookRepository->method('selectByTitle')->willReturn(null);
        $this->bookService->selectByTitle("WRONG");
    }
    
    //UPDATE TESTS
    public function testBadUpdate():void{
        $this->expectException(ServiceException::class);                
        $this->bookRepository->method('findById')->willReturn(null);
        $this->bookService->update($this->sampleObject);
    }
    
    //DELETE TESTS
    public function testBadDelete():void{
        $this->expectException(ServiceException::class);
        
        $this->bookRepository->method('findById')->willReturn(null);
        
        $this->bookService->delete("ObjID99");
    }       
}
