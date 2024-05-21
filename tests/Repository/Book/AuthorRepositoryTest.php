<?php
declare(strict_types=1);

namespace App\Test\Repository\Book;

use App\Test\Repository\BaseRepositoryTest;
use App\Repository\Book\AuthorRepository;
use App\Models\Book\Author;

final class AuthorRepositoryTest extends BaseRepositoryTest
{
    public static AuthorRepository $authorRepository;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        self::$authorRepository = new AuthorRepository(self::$pdo);
    }

    public function setUp():void{
        //Author saved to test duplicated author errors
        $author= new Author('Mario',"Rossi");
        self::$authorRepository->save($author);        
    }

    public function tearDown():void{
        //Clear the table
        self::$pdo->exec("SET FOREIGN_KEY_CHECKS=0; TRUNCATE TABLE Author; SET FOREIGN_KEY_CHECKS=1;");
    }

    //INSERT TESTS
    public function testGoodInsert():void{                
        $author= new Author('Luca',"Verdi");

        self::$authorRepository->save($author);

        $this->assertEquals(self::$authorRepository->findById(2)->firstname,"Luca");
    }
    
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->assertNotNull(self::$authorRepository->findById(1));
    }
    
    public function testBadSelectById(): void
    {
        $this->assertNull(self::$authorRepository->findById(3));
    }
    
    public function testGoodSelectByKey(): void
    {
        $this->assertNotEmpty(self::$authorRepository->findByQuery("mari"));
    }
    
    public function testBadSelectByFullName(): void
    {
        $this->assertEmpty(self::$authorRepository->findByQuery("WRONG-AUTHOR-NAME"));
    }
    
    
    public function testGoodSelectAll():void{
        $author1 = new Author('Sara',"Neri");
        $author2 = new Author('Tommaso',"Gialli");
        $author3 = new Author('Franco',"Verdi");
        self::$authorRepository->save($author1);
        self::$authorRepository->save($author2);
        self::$authorRepository->save($author3);
        
        $authors = self::$authorRepository->find();
        
        $this->assertEquals(count($authors),4);
        $this->assertNotNull($authors[1]);       
    }    
    
    //UPDATE TESTS
    public function testGoodUpdate():void{
        $author= new Author('Andrea',"Rossi",1);
        
        self::$authorRepository->update($author);
        
        $this->assertEquals("Andrea",self::$authorRepository->findById(1)->firstname);
    }
    
    //DELETE TESTS
    public function testGoodDelete():void{       
        
        self::$authorRepository->delete(1);
        
        $this->assertNull(self::$authorRepository->findById(1));
    }
}