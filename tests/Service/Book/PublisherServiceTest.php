<?php

declare(strict_types=1);

namespace App\Test\Service\Book;

use App\Exception\ServiceException;
use App\Model\Book\Publisher;
use App\Model\Computer\Cpu;
use App\Repository\Book\PublisherRepository;
use App\Service\Book\PublisherService;
use App\Test\Service\BaseServiceTest;
use PHPUnit\Framework\TestCase;
use PDO;
use PDOStatement;

final class PublisherServiceTest extends BaseServiceTest
{
    public PublisherService $publisherService;
    public PublisherRepository $publisherRepository;
    
    public function setUp(): void
    {
        $this->publisherRepository = $this->createMock(PublisherRepository::class);
        $this->publisherService = new PublisherService($this->publisherRepository);

        $this->sampleObject = new Publisher(name: "Mondadori", id: 1);
    }
    
    //INSERT TESTS
    public function testGoodInsert():void{
        $this->expectNotToPerformAssertions();
        $this->publisherRepository->method('findFirst')->willReturn(null);
        $publisher = new Publisher("pub",1);
        $this->publisherService->save($publisher);
    }

    public function testBadInsert(): void
    {
        $this->expectException(ServiceException::class);
        $this->publisherRepository->method('findFirst')->willReturn($this->sampleObject);
        $publisher = new Publisher("pub",1);
        $this->publisherService->save($publisher);
    }
        
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->publisherRepository->method('findById')->willReturn($this->sampleObject);
        $this->assertEquals("Mondadori",$this->publisherService->findById(1)->name);
    }
    
    public function testBadSelectById(): void
    {
        $this->expectException(ServiceException::class);
        $this->publisherRepository->method('findById')->willReturn(null);
        $this->publisherService->findById(2);
    }
    
    //UPDATE TESTS
    public function testBadUpdate():void{
        $this->expectException(ServiceException::class);
        $publisher = new Publisher("WRONG PUBLISHER",15);

        $this->publisherRepository->method('findById')->willReturn(null);
        $this->publisherService->update($publisher);
    }
    
    //DELETE TESTS
    public function testBadDelete():void{
        $this->expectException(ServiceException::class);

        $this->publisherRepository->method('findById')->willReturn(null);
        
        $this->publisherService->delete(5);
    }   
}