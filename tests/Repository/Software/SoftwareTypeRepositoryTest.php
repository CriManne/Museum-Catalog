<?php
declare(strict_types=1);

namespace App\Test\Repository;

use PDO;
use PHPUnit\Framework\TestCase;
use App\Repository\Software\SoftwareTypeRepository;
use App\Exception\RepositoryException;
use App\Model\Software\SoftwareType;

final class SoftwareTypeRepositoryTest extends TestCase
{
    public static SoftwareTypeRepository $softwareTypeRepository;
    public static ?PDO $pdo;

    public static function setUpBeforeClass(): void
    {
        self::$pdo = RepositoryTestUtil::getTestPdo();

        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);
        self::$pdo = RepositoryTestUtil::createTestDB(self::$pdo);

        self::$softwareTypeRepository = new SoftwareTypeRepository(self::$pdo);          
    }

    public function setUp():void{
        //Support inserted to test duplicated supports errors
        $softwareType = new SoftwareType(null,'Office',null);
        self::$softwareTypeRepository->insert($softwareType);
    }

    public function tearDown():void{
        //Clear the table
        self::$pdo->exec("SET FOREIGN_KEY_CHECKS=0; TRUNCATE TABLE softwaretype; SET FOREIGN_KEY_CHECKS=1;");
    }

    //INSERT TESTS
    public function testGoodInsert():void{                
        $softwareType = new SoftwareType(null,'Game');

        self::$softwareTypeRepository->insert($softwareType);

        $this->assertEquals(self::$softwareTypeRepository->selectById(2)->Name,"Game");
    }
    public function testBadInsert():void{        
        $this->expectException(RepositoryException::class);

        //SoftwareType already inserted in the setUp() method
        $softwareType = new SoftwareType(null,'Office');

        self::$softwareTypeRepository->insert($softwareType);
    }
    
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->assertNotNull(self::$softwareTypeRepository->selectById(1));
    }
    
    public function testBadSelectById(): void
    {
        $this->assertNull(self::$softwareTypeRepository->selectById(3));
    }
    
    public function testGoodSelectByName(): void
    {
        $this->assertNotNull(self::$softwareTypeRepository->selectByName("Office"));
    }
    
    public function testBadSelectByCredentials(): void
    {
        $this->assertNull(self::$softwareTypeRepository->selectByName("WRONG-SOFTWAERE-TYPE"));
    }
    
    
    public function testGoodSelectAll():void{
        $softwareType1 = new SoftwareType(null,'S1');
        $softwareType2 = new SoftwareType(null,'S2');
        $softwareType3 = new SoftwareType(null,'S3');
        self::$softwareTypeRepository->insert($softwareType1);
        self::$softwareTypeRepository->insert($softwareType2);
        self::$softwareTypeRepository->insert($softwareType3);
        
        $softwareTypes = self::$softwareTypeRepository->selectAll();
        
        $this->assertEquals(count($softwareTypes),4);
        $this->assertNotNull($softwareTypes[1]);       
    }    
    
    //UPDATE TESTS
    public function testGoodUpdate():void{
        $softwareType = new SoftwareType(1,'Game');
        
        self::$softwareTypeRepository->update($softwareType);
        
        $this->assertEquals("Game",self::$softwareTypeRepository->selectById(1)->Name);
    }
    
    //DELETE TESTS
    public function testGoodDelete():void{       
        
        self::$softwareTypeRepository->delete(1);
        
        $this->assertNull(self::$softwareTypeRepository->selectById(1));
        $this->assertNotNull(self::$softwareTypeRepository->selectById(1,true));
    }
    
    public static function tearDownAfterClass():void{
        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);        
        self::$pdo = null;
    }    
}