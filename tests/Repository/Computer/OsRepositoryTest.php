<?php
declare(strict_types=1);

namespace App\Test\Repository\Computer;

use App\Test\Repository\RepositoryTestUtil;
use PDO;
use PHPUnit\Framework\TestCase;
use App\Repository\Computer\OsRepository;
use App\Exception\RepositoryException;
use App\Model\Computer\Os;

final class OsRepositoryTest extends TestCase
{
    public static OsRepository $osRepository;
    public static ?PDO $pdo;

    public static function setUpBeforeClass(): void
    {
        self::$pdo = RepositoryTestUtil::getTestPdo();

        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);
        self::$pdo = RepositoryTestUtil::createTestDB(self::$pdo);

        self::$osRepository = new OsRepository(self::$pdo);          
    }

    public function setUp():void{
        //Os inserted to test duplicated os errors
        $os = new Os('Windows');
        self::$osRepository->insert($os);
    }

    public function tearDown():void{
        //Clear the table
        self::$pdo->exec("SET FOREIGN_KEY_CHECKS=0; TRUNCATE TABLE Os; SET FOREIGN_KEY_CHECKS=1;");
    }

    //INSERT TESTS
    public function testGoodInsert():void{                
        $os = new Os('Linux');

        self::$osRepository->insert($os);

        $selectResult = self::$osRepository->findById(2);

        $this->assertEquals($os->name,$selectResult->name);
    }
    public function testBadInsert():void{        
        $this->expectException(RepositoryException::class);

        //Os already inserted in the setUp() method
        $os = new Os('Windows');

        self::$osRepository->insert($os);
    }
    
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->assertNotNull(self::$osRepository->findById(1));
    }
    
    public function testBadSelectById(): void
    {
        $this->assertNull(self::$osRepository->findById(3));
    }
    
    public function testGoodSelectByName(): void
    {
        $this->assertNotNull(self::$osRepository->selectByName("Windows"));
    }
    
    public function testBadSelectByName(): void
    {
        $this->assertNull(self::$osRepository->selectByName("WRONG-OS-NAME"));
    }

    public function testGoodSelectByKey(): void
    {
        $this->assertNotEmpty(self::$osRepository->selectByKey("indow"));
    }
    
    public function testBadSelectByKey(): void
    {
        $this->assertEmpty(self::$osRepository->selectByKey("WRONG-OS-NAME"));
    }
    
    
    public function testGoodSelectAll():void{
        $os1 = new Os('S1');
        $os2 = new Os('S2');
        $os3 = new Os('S3');
        self::$osRepository->insert($os1);
        self::$osRepository->insert($os2);
        self::$osRepository->insert($os3);
        
        $oss = self::$osRepository->selectAll();
        
        $this->assertEquals(count($oss),4);
        $this->assertNotNull($oss[1]);       
    }    
    
    //UPDATE TESTS
    public function testGoodUpdate():void{
        $os = new Os('Linux',1);
        
        self::$osRepository->update($os);
        
        $this->assertEquals("Linux",self::$osRepository->findById(1)->name);
    }
    
    //DELETE TESTS
    public function testGoodDelete():void{       
        
        self::$osRepository->delete(1);
        
        $this->assertNull(self::$osRepository->findById(1));
    }
    
    public static function tearDownAfterClass():void{
        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);        
        self::$pdo = null;
    }    
}