<?php
declare(strict_types=1);

namespace App\Test\Repository\Book;

use App\Test\Repository\RepositoryTestUtil;
use PDO;
use PHPUnit\Framework\TestCase;
use App\Repository\Book\AuthorRepository;
use App\Model\Book\Author;

final class AuthorRepositoryTest extends TestCase
{
    public static AuthorRepository $authorRepository;
    public static ?PDO $pdo;

    public static function setUpBeforeClass(): void
    {
        self::$pdo = RepositoryTestUtil::getTestPdo();

        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);
        self::$pdo = RepositoryTestUtil::createTestDB(self::$pdo);

        self::$authorRepository = new AuthorRepository(self::$pdo);          
    }

    public function setUp():void{
        //Author inserted to test duplicated author errors
        $author= new Author('Mario',"Rossi");
        self::$authorRepository->insert($author);        
    }

    public function tearDown():void{
        //Clear the table
        self::$pdo->exec("SET FOREIGN_KEY_CHECKS=0; TRUNCATE TABLE Author; SET FOREIGN_KEY_CHECKS=1;");
    }

    //INSERT TESTS
    public function testGoodInsert():void{                
        $author= new Author('Luca',"Verdi");

        self::$authorRepository->insert($author);

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
        $this->assertNotEmpty(self::$authorRepository->findByKey("mario ros"));
    }
    
    public function testBadSelectByFullName(): void
    {
        $this->assertEmpty(self::$authorRepository->findByKey("WRONG-AUTHOR-NAME"));
    }
    
    
    public function testGoodSelectAll():void{
        $author1 = new Author('Sara',"Neri");
        $author2 = new Author('Tommaso',"Gialli");
        $author3 = new Author('Franco',"Verdi");
        self::$authorRepository->insert($author1);
        self::$authorRepository->insert($author2);
        self::$authorRepository->insert($author3);
        
        $authors = self::$authorRepository->findAll();
        
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
    
    public static function tearDownAfterClass():void{
        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);        
        self::$pdo = null;
    }    
}