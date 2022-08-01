<?php
declare(strict_types=1);

namespace App\Test\Repository;

use PDO;
use PHPUnit\Framework\TestCase;
use App\Repository\Software\SupportTypeRepository;
use App\Exception\RepositoryException;
use App\Model\Software\SupportType;

final class SupportTypeRepositoryTest extends TestCase
{
    public static SupportTypeRepository $supportTypeRepository;
    public static ?PDO $pdo;

    public static function setUpBeforeClass(): void
    {
        self::$pdo = RepositoryTestUtil::getTestPdo();

        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);
        self::$pdo = RepositoryTestUtil::createTestDB(self::$pdo);

        self::$supportTypeRepository = new SupportTypeRepository(self::$pdo);          
    }

    public function setUp():void{
        //User inserted to test duplicated user errors
        $supportType = new SupportType(null,'CD-ROM');
        self::$supportTypeRepository->insertSupport($supportType);
    }

    public function tearDown():void{
        //Clear the user table
        self::$pdo->exec("SET FOREIGN_KEY_CHECKS=0; TRUNCATE TABLE supporttype; SET FOREIGN_KEY_CHECKS=1;");
    }

    //INSERT TESTS
    public function testGoodInsert():void{                
        $supportType = new SupportType(null,'FLOPPY');

        self::$supportTypeRepository->insertSupport($supportType);

        $this->assertEquals(self::$supportTypeRepository->selectById(2)->Name,"FLOPPY");
    }
    public function testBadInsert():void{        
        $this->expectException(RepositoryException::class);

        //SupportType already inserted in the setUp() method
        $supportType = new SupportType(null,'CD-ROM');

        self::$supportTypeRepository->insertSupport($supportType);
    }
    
    //SELECT TESTS
    public function testGoodSelectById(): void
    {
        $this->assertNotNull(self::$supportTypeRepository->selectById(1));
    }
    
    public function testBadSelectById(): void
    {
        $this->assertNull(self::$supportTypeRepository->selectById(3));
    }
    
    public function testGoodSelectByName(): void
    {
        $this->assertNotNull(self::$supportTypeRepository->selectByName("CD-ROM"));
    }
    
    public function testBadSelectUserByCredentials(): void
    {
        $this->assertNull(self::$supportTypeRepository->selectByName("WRONG-SUPPORT"));
    }
    
    
    public function testGoodSelectAll():void{
        $supportType1 = new SupportType(null,'S1');
        $supportType2 = new SupportType(null,'S2');
        $supportType3 = new SupportType(null,'S3');
        self::$supportTypeRepository->insertSupport($supportType1);
        self::$supportTypeRepository->insertSupport($supportType2);
        self::$supportTypeRepository->insertSupport($supportType3);
        
        $supportTypes = self::$supportTypeRepository->selectAll();
        
        $this->assertEquals(count($supportTypes),4);
        $this->assertNotNull($supportTypes[1]);       
    }    
    
    //UPDATE TESTS
    public function testGoodUpdateUser():void{
        $supportType = new SupportType(1,'FLOPPY');
        
        self::$supportTypeRepository->updateSupport($supportType);
        
        $this->assertEquals("FLOPPY",self::$supportTypeRepository->selectById(1)->Name);
    }
    
    /*
    //DELETE TESTS
    public function testGoodDeleteUser():void{
        $email = "testemail@gmail.com";
        
        self::$supportTypeRepository->deleteUser($email);
        
        $this->assertNull(self::$supportTypeRepository->selectById("testemail@gmail.com"));
    }

    public static function tearDownAfterClass():void{
        self::$pdo = RepositoryTestUtil::dropTestDB(self::$pdo);        
        self::$pdo = null;
    }
    */   
}