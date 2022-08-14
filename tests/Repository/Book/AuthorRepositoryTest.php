<?php
declare(strict_types=1);

namespace App\Test\Repository;

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
        //Author inserted to test duplicated cpu errors
        $author= new Author(null,'Mario',"Rossi",null);
        self::$authorRepository->insert($author);        
    }

    public function tearDown():void{
        //Clear the table
        self::$pdo->exec("SET FOREIGN_KEY_CHECKS=0; TRUNCATE TABLE author; SET FOREIGN_KEY_CHECKS=1;");
    }

    //INSERT TESTS
    public function testGoodInsert():void{                
        $author= new Author(null,'Luca',"Verdi",null);

        self::$authorRepository->insert($author);

        $this->assertEquals(self::$authorRepository->selectById(2)->firstname,"Luca");
    }
    
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->assertNotNull(self::$authorRepository->selectById(1));
    }
    
    public function testBadSelectById(): void
    {
        $this->assertNull(self::$authorRepository->selectById(3));
    }
    
    public function testGoodSelectByFullName(): void
    {
        $this->assertNotNull(self::$authorRepository->selectByFullName("Mario Rossi"));
    }
    
    public function testBadSelectByFullName(): void
    {
        $this->assertNull(self::$authorRepository->selectByFullName("WRONG-AUTHOR-NAME"));
    }
    
    
    public function testGoodSelectAll():void{
        $author1 = new Author(null,'Sara',"Neri",null);
        $author2 = new Author(null,'Tommaso',"Gialli",null);
        $author3 = new Author(null,'Franco',"Verdi",null);
        self::$authorRepository->insert($author1);
        self::$authorRepository->insert($author2);
        self::$authorRepository->insert($author3);
        
        $authors = self::$authorRepository->selectAll();
        
        $this->assertEquals(count($authors),4);
        $this->assertNotNull($authors[1]);       
    }    
    
    //UPDATE TESTS
    public function testGoodUpdate():void{
        $author= new Author(1,'Andrea',"Rossi",null);
        
        self::$authorRepository->update($author);
        
        $this->assertEquals("Andrea",self::$authorRepository->selectById(1)->firstname);
    }
    
    //DELETE TESTS
    public function testGoodDelete():void{       
        
        self::$authorRepository->delete(1);
        
        $this->assertNull(self::$authorRepository->selectById(1));
        $this->assertNotNull(self::$authorRepository->selectById(1,true));
    }
    
    public static function tearDownAfterClass():void{
        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);        
        self::$pdo = null;
    }    
}