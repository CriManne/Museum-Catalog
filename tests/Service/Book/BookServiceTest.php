<?php
declare(strict_types=1);

namespace App\Test\Service\Book;

use App\Models\GenericObject;
use App\Repository\GenericObjectRepository;
use App\Test\Service\BaseServiceTest;
use App\Exception\ServiceException;
use App\Models\Book\Book;
use App\Models\Book\Author;
use App\Models\Book\Publisher;

use App\Repository\Book\BookRepository;
use App\Service\Book\BookService;

final class BookServiceTest extends BaseServiceTest
{
    public GenericObjectRepository $genericObjectRepository;
    public BookRepository $bookRepository;
    public BookService $bookService;
    

    public function setUp(): void
    {                
        $this->bookRepository = $this->createMock(BookRepository::class);

        $this->bookService = new BookService($this->bookRepository);

        $this->sampleGenericObject = new GenericObject("objID");

        $this->sampleObject = new Book(
            $this->sampleGenericObject,
            '1984',
            new Publisher("Mondadori",1),
            1945,
            [
                new Author("George","Orwell",1)
            ],
            "ALSKDI82SB",
            245,
        );
    }
    
    
    //INSERT TESTS
    
    public function testBadInsert():void{
        $this->expectException(ServiceException::class);
        $this->bookRepository->method('findFirst')->willReturn($this->sampleObject);
        $this->bookService->save($this->sampleObject);
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
